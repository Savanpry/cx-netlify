<?php

declare(strict_types=1);

/** POST /api/upload — handle a single image upload (multipart field "file"). */
class UploadController
{
    public function store(array $authUser): void
    {
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Response::error('No file uploaded or upload error', 400);
        }

        $file = $_FILES['file'];

        if ($file['size'] > MAX_UPLOAD_BYTES) {
            Response::error('File exceeds maximum allowed size', 413);
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!in_array($mime, ALLOWED_IMAGE_TYPES, true)) {
            Response::error('Unsupported file type', 415);
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0775, true);
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'bin',
        };
        $base = date('Ymd') . '_' . bin2hex(random_bytes(8));
        $name = $base . '.' . $ext;
        $dest = UPLOAD_DIR . '/' . $name;

        // "featured" uploads are normalised to 1920x1080 and get a 380x220 thumb.
        // Everything else (e.g. inline editor images) is stored as-is.
        $variant = $_POST['variant'] ?? ($_GET['variant'] ?? 'default');

        if ($variant === 'featured') {
            $src = $this->loadImage($file['tmp_name'], $mime);
            if (!$src) {
                Response::error('Could not read the uploaded image', 422);
            }

            // Main copy: 1920x1080.
            $main = $this->coverCrop($src, FEATURED_IMAGE_WIDTH, FEATURED_IMAGE_HEIGHT);
            $this->saveImage($main, $dest, $mime);
            imagedestroy($main);

            // Thumbnail copy: 380x220.
            $thumbName = $base . '_thumb.' . $ext;
            $thumbDest = UPLOAD_DIR . '/' . $thumbName;
            $thumb = $this->coverCrop($src, FEATURED_THUMB_WIDTH, FEATURED_THUMB_HEIGHT);
            $this->saveImage($thumb, $thumbDest, $mime);
            imagedestroy($thumb);

            imagedestroy($src);

            Response::success([
                'filename'       => $name,
                'url'            => UPLOAD_URL . '/' . $name,
                'thumb_filename' => $thumbName,
                'thumb_url'      => UPLOAD_URL . '/' . $thumbName,
                'width'          => FEATURED_IMAGE_WIDTH,
                'height'         => FEATURED_IMAGE_HEIGHT,
            ], 'File uploaded', 201);
        }

        if ($variant === 'author_avatar') {
            $src = $this->loadImage($file['tmp_name'], $mime);
            if (!$src) {
                Response::error('Could not read the uploaded image', 422);
            }

            $avatar = $this->coverCrop($src, AUTHOR_AVATAR_WIDTH, AUTHOR_AVATAR_HEIGHT);
            $this->saveImage($avatar, $dest, $mime);
            imagedestroy($avatar);
            imagedestroy($src);

            Response::success([
                'filename' => $name,
                'url'      => UPLOAD_URL . '/' . $name,
                'width'    => AUTHOR_AVATAR_WIDTH,
                'height'   => AUTHOR_AVATAR_HEIGHT,
            ], 'File uploaded', 201);
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            Response::error('Failed to store uploaded file', 500);
        }

        Response::success([
            'filename' => $name,
            'url'      => UPLOAD_URL . '/' . $name,
        ], 'File uploaded', 201);
    }

    // -- image helpers --------------------------------------------------

    /** Create a GD image resource from a file, by mime type. */
    private function loadImage(string $path, string $mime): ?\GdImage
    {
        $img = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png'  => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            'image/gif'  => imagecreatefromgif($path),
            default      => false,
        };
        return $img ?: null;
    }

    /**
     * Resize + centre-crop ("cover") an image to exactly $tw x $th, preserving
     * aspect ratio (no distortion). Returns a new GD image.
     */
    private function coverCrop(\GdImage $src, int $tw, int $th): \GdImage
    {
        $w = imagesx($src);
        $h = imagesy($src);

        $srcRatio = $w / $h;
        $dstRatio = $tw / $th;

        if ($srcRatio > $dstRatio) {
            // Source is wider: crop the sides.
            $cropH = $h;
            $cropW = (int) round($h * $dstRatio);
            $srcX  = (int) round(($w - $cropW) / 2);
            $srcY  = 0;
        } else {
            // Source is taller: crop top/bottom.
            $cropW = $w;
            $cropH = (int) round($w / $dstRatio);
            $srcX  = 0;
            $srcY  = (int) round(($h - $cropH) / 2);
        }

        $dst = imagecreatetruecolor($tw, $th);
        // Preserve transparency for png / webp / gif.
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $tw, $th, $transparent);

        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $tw, $th, $cropW, $cropH);

        return $dst;
    }

    /** Save a GD image to a path in the format implied by $mime. */
    private function saveImage(\GdImage $img, string $path, string $mime): void
    {
        switch ($mime) {
            case 'image/png':
                imagepng($img, $path, 6);
                break;
            case 'image/webp':
                imagewebp($img, $path, 85);
                break;
            case 'image/gif':
                imagegif($img, $path);
                break;
            case 'image/jpeg':
            default:
                imagejpeg($img, $path, 85);
                break;
        }
    }
}
