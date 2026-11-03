<?php

include("maildetails.php"); // SMTP credentials

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Chatbot ROI Calculator - Backend Email Handler (SMTP Version)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

require 'vendor/autoload.php';

header('Content-Type: application/json');

// Increase limits for PDF generation
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');

// Get POST data
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (!$data || !isset($data['email'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
  exit;
}

$results = $data['results'];
$inputs = $data['inputs'];
$industry = $data['industry'] ?: 'Not Specified';

// Additional calculations for the new template
$companyName = $inputs['companyName'] ?? 'Not Specified';
$currentDate = date('M d, Y');

$savingsStr = $results['savings'];
$savingsNum = floatval(preg_replace('/[^0-9.]/', '', $savingsStr));
$savingsFormatted = number_format($savingsNum);
$annualCostNum = floatval(preg_replace('/[^0-9.]/', '', $results['annualCost']));
$chatbotCostNum = floatval(preg_replace('/[^0-9.]/', '', $inputs['chatbotCost']));

$revenueUpliftNum = $savingsNum * 0.15; // Just a placeholder uplift if not provided
$revenueUplift = number_format($revenueUpliftNum);

$monthsToBreakeven = $savingsNum > 0 ? round(($chatbotCostNum / $savingsNum) * 12, 1) : 0;
$timeSaved = number_format((float)preg_replace('/[^0-9.]/', '', $results['timeSaved']));
$tickets = number_format((float)preg_replace('/[^0-9.]/', '', $inputs['ticketsPerMonth']));
$timePerTicket = $inputs['minutesPerTicket'];

$currentCostMonthly = number_format($annualCostNum / 12);
$newCostMonthly = number_format(($annualCostNum - $savingsNum + $chatbotCostNum) / 12);
$currentAnnual = number_format($annualCostNum);
$newAnnual = number_format($annualCostNum - $savingsNum + $chatbotCostNum);
$netGainNum = $savingsNum - $chatbotCostNum;
$netGain = number_format($netGainNum);

$roiConservative = $chatbotCostNum > 0 ? round((($savingsNum - $chatbotCostNum) * 0.5 / $chatbotCostNum) * 100) : 0;
$roiExpected = floatval(preg_replace('/[^0-9.]/', '', $results['roi']));
$roiAggressive = $chatbotCostNum > 0 ? round((($savingsNum - $chatbotCostNum) * 1.5 / $chatbotCostNum) * 100) : 0;

$lostCost = number_format($savingsNum);

// Use Cases based on industry or defaults
$useCases = [
  'Automated Ticket Triage & Resolution',
  '24/7 Intelligent Lead Qualification',
  'Autonomous Multi-lingual Customer Support'
];

// Industry specific overrides
if (strpos($industry, 'retail') !== false) {
  $useCases = ['Automated Order Tracking & Returns', 'AI-Powered Product Recommendations', '24/7 Shopping Assistance'];
} elseif (strpos($industry, 'financial') !== false) {
  $useCases = ['Automated Claims Processing', 'Fraud Detection Alerts', 'Instant Policy Information'];
} elseif (strpos($industry, 'healthcare') !== false) {
  $useCases = ['Smart Appointment Scheduling', 'Patient Triage Automation', 'Instant Health FAQ Resolution'];
}

$pdfTemplate = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ROI Report</title>
<style>
  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    background-color: #0A1628;
    color: #E8F0FE;
    margin: 0;
    padding: 0;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }
  table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
  a { text-decoration: none; }
  .mono { font-family: 'Courier New', Courier, monospace; }
</style>
</head>
<body style="background-color: #0A1628; margin: 0; padding: 0; font-family: DejaVu Sans, Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0A1628;">
  <tr>
    <td align="center" style="padding: 20px 0;">
      
      <!-- WRAPPER -->
      <table width="800" cellpadding="0" cellspacing="0" class="main-wrapper" style="background-color: #0A1628; max-width: 800px; margin: 0 auto;">
        
        <!-- HEADER -->
        <tr class="section">
          <td style="padding: 32px 40px 28px; border-bottom: 1px solid rgba(0,200,160,0.2); background: #0A1628; background: linear-gradient(135deg, #0A1628 0%, #0F2347 60%, #0A2A1E 100%);">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="36" height="36" align="center" valign="middle" style="border-radius: 8px;">
                        <img src="https://www.cypherox.com/assets/img/cypherox-logo.png" width="200" height="200" style="display: block; border: 0;" alt="C">
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="right">
                  <span style="background: rgba(0,200,160,0.1); border: 1px solid rgba(0,200,160,0.2); border-radius: 20px; padding: 6px 14px; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: #00C8A0; font-family: 'Courier New', monospace;">Agentic ROI Report</span>
                </td>
              </tr>
            </table>
            
            <div style="font-size: 28px; font-weight: 700; letter-spacing: -0.8px; line-height: 1.2; margin-top: 24px; margin-bottom: 8px; color: #E8F0FE;">Your <span style="color: #00C8A0;">Autonomous AI</span> ROI Report</div>
            <div style="font-size: 13px; color: #8BA3C7; line-height: 1.6;">Prepared by Cypherox Technologies &mdash; custom software, AI &amp; digital solutions</div>
            
            <table cellpadding="0" cellspacing="0" style="margin-top: 20px;">
              <tr>
                <td style="padding-right: 8px;">
                  <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 5px 12px; font-size: 12px; font-family: 'Courier New', monospace; color: #B0C4DE;">
                    <span style="color: #8BA3C7; font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; margin-right: 6px;">Client</span> {$companyName}
                  </div>
                </td>
                <td style="padding-right: 8px;">
                  <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 5px 12px; font-size: 12px; font-family: 'Courier New', monospace; color: #B0C4DE;">
                    <span style="color: #8BA3C7; font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; margin-right: 6px;">Industry</span> {$industry}
                  </div>
                </td>
                <td>
                  <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 5px 12px; font-size: 12px; font-family: 'Courier New', monospace; color: #B0C4DE;">
                    <span style="color: #8BA3C7; font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; margin-right: 6px;">Date</span> {$currentDate}
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- BOTTOM LINE -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">01 &mdash; The Bottom Line</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; letter-spacing: -0.3px; color: #E8F0FE;">Your team is trapped in manual, repetitive loops.</div>
            <div style="font-size: 13px; color: #8BA3C7; line-height: 1.7; margin-bottom: 20px;">Moving to an Autonomous AI Agent model doesn't just save time &mdash; it shifts your resources from support tickets to high-value growth tasks.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="48%" valign="top">
                  <div style="background: rgba(0,200,160,0.06); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; overflow: hidden;">
                    <div style="height: 2px; width: 100%; background-color: #00C8A0; background: linear-gradient(90deg, #00C8A0, transparent); border-top-left-radius: 11px; border-top-right-radius: 11px;"></div>
                    <div style="padding: 16px 20px 18px;">
                      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #8BA3C7; margin-bottom: 8px; font-family: 'Courier New', monospace;">Estimated Annual Savings</div>
                      <div style="font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: #00C8A0;">₹{$savingsFormatted}</div>
                    </div>
                  </div>
                </td>
                <td width="4%"></td>
                <td width="48%" valign="top">
                  <div style="background: rgba(0,200,160,0.06); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; overflow: hidden;">
                    <div style="height: 2px; width: 100%; background-color: #00C8A0; background: linear-gradient(90deg, #00C8A0, transparent); border-top-left-radius: 11px; border-top-right-radius: 11px;"></div>
                    <div style="padding: 16px 20px 18px;">
                      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #8BA3C7; margin-bottom: 8px; font-family: 'Courier New', monospace;">Revenue Uplift</div>
                      <div style="font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: #00C8A0;">₹{$revenueUplift}</div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr><td colspan="3" height="12"></td></tr>
              <tr>
                <td width="48%" valign="top">
                  <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 18px 20px; overflow: hidden;">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #8BA3C7; margin-bottom: 8px; font-family: 'Courier New', monospace;">Time Recaptured</div>
                    <div style="font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: #E8F0FE;">{$timeSaved} hrs</div>
                  </div>
                </td>
                <td width="4%"></td>
                <td width="48%" valign="top">
                  <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 18px 20px; overflow: hidden;">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #8BA3C7; margin-bottom: 8px; font-family: 'Courier New', monospace;">Break-Even Point</div>
                    <div style="font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: #E8F0FE;">{$monthsToBreakeven} months</div>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- COMPARISON TABLE -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">02 &mdash; Current vs. Autonomous Model</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; letter-spacing: -0.3px; color: #E8F0FE;">Stop wasting human talent on manual tasks.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px;">
              <thead>
                <tr>
                  <th align="left" style="padding: 10px 14px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #8BA3C7; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.08); font-family: 'Courier New', monospace;">Metric</th>
                  <th align="left" style="padding: 10px 14px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #8BA3C7; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.08); font-family: 'Courier New', monospace;">Current Manual Model</th>
                  <th align="left" style="padding: 10px 14px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: #8BA3C7; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.08); font-family: 'Courier New', monospace;">AI Agent Model</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04); color: #C8D8F0;">Monthly Volume</td>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <span style="background: rgba(231,76,60,0.12); color: #FF8070; border: 1px solid rgba(231,76,60,0.2); border-radius: 4px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">{$tickets} requests</span>
                  </td>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <span style="background: rgba(0,200,160,0.12); color: #00C8A0; border: 1px solid rgba(0,200,160,0.2); border-radius: 4px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">{$tickets} handled</span>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04); color: #C8D8F0;">Handling Time</td>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <span style="background: rgba(231,76,60,0.12); color: #FF8070; border: 1px solid rgba(231,76,60,0.2); border-radius: 4px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">{$timePerTicket} mins</span>
                  </td>
                  <td style="padding: 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <span style="background: rgba(0,200,160,0.12); color: #00C8A0; border: 1px solid rgba(0,200,160,0.2); border-radius: 4px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">Near-zero</span>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 12px 14px; color: #C8D8F0;">Cost / Month</td>
                  <td style="padding: 12px 14px;">
                    <span style="background: rgba(231,76,60,0.12); color: #FF8070; border: 1px solid rgba(231,76,60,0.2); border-radius: 6px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">₹{$currentCostMonthly}</span>
                  </td>
                  <td style="padding: 12px 14px;">
                    <span style="background: rgba(0,200,160,0.12); color: #00C8A0; border: 1px solid rgba(0,200,160,0.2); border-radius: 6px; padding: 2px 8px; font-size: 11px; font-family: 'Courier New', monospace;">₹{$newCostMonthly}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <!-- FINANCIAL IMPACT -->
        <tr class="section section-break">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">03 &mdash; Financial Impact</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; letter-spacing: -0.3px; color: #E8F0FE;">Your numbers, side by side.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="31%" valign="top">
                  <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; text-align: center; overflow: hidden;">
                    <div style="font-size: 11px; color: #8BA3C7; margin-bottom: 6px; font-family: 'Courier New', monospace; text-transform: uppercase; letter-spacing: 0.8px;">Current Annual Cost</div>
                    <div style="font-size: 16px; font-weight: 600; color: #FF8070;">₹{$currentAnnual}</div>
                  </div>
                </td>
                <td width="3%"></td>
                <td width="32%" valign="top">
                  <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; text-align: center; overflow: hidden;">
                    <div style="font-size: 11px; color: #8BA3C7; margin-bottom: 6px; font-family: 'Courier New', monospace; text-transform: uppercase; letter-spacing: 0.8px;">Projected Annual Cost</div>
                    <div style="font-size: 16px; font-weight: 600; color: #B0C4DE;">₹{$newAnnual}</div>
                  </div>
                </td>
                <td width="3%"></td>
                <td width="31%" valign="top">
                  <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; text-align: center; overflow: hidden;">
                    <div style="font-size: 11px; color: #8BA3C7; margin-bottom: 6px; font-family: 'Courier New', monospace; text-transform: uppercase; letter-spacing: 0.8px;">Net Annual Gain</div>
                    <div style="font-size: 16px; font-weight: 600; color: #00C8A0;">₹{$netGain}</div>
                  </div>
                </td>
              </tr>
            </table>
            <div style="margin-top:12px; font-size:11px; color:#8BA3C7; font-family:'Courier New', monospace; padding:0 4px;">
              ROI = (Net Gain &divide; Investment) &times; 100
            </div>
          </td>
        </tr>

        <!-- WHY AI AGENTS (SECTION 04) -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">04 &mdash; Why You Need AI Agents</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; letter-spacing: -0.3px; color: #E8F0FE;">The proactive edge.</div>
            <div style="font-size: 13px; color: #8BA3C7; line-height: 1.7; margin-bottom: 20px;">Unlike legacy bots that just "chat," our Agents qualify leads and resolve issues autonomously 24/7. We focus on these high-impact automation areas:</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding-bottom: 12px;">
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="40" style="vertical-align: middle;">
                          <div class="section-04-circle" style="width: 24px; height: 24px; background: rgba(0,200,160,0.15); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; color: #00C8A0;">1</div>
                        </td>
                        <td style="font-size: 14px; color: #E8F0FE; font-weight: 500; vertical-align: middle;">{$useCases[0]}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
              <tr>
                <td style="padding-bottom: 12px;">
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="40" style="vertical-align: middle;">
                          <div class="section-04-circle" style="width: 24px; height: 24px; background: rgba(0,200,160,0.15); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; color: #00C8A0;">2</div>
                        </td>
                        <td style="font-size: 14px; color: #E8F0FE; font-weight: 500; vertical-align: middle;">{$useCases[1]}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="40" style="vertical-align: middle;">
                          <div class="section-04-circle" style="width: 24px; height: 24px; background: rgba(0,200,160,0.15); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; color: #00C8A0;">3</div>
                        </td>
                        <td style="font-size: 14px; color: #E8F0FE; font-weight: 500; vertical-align: middle;">{$useCases[2]}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- REVENUE OPPORTUNITY (SECTION 05) -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">05 &mdash; Revenue Opportunity</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 8px; letter-spacing: -0.3px; color: #E8F0FE;">Turn support into a revenue engine.</div>
            <div style="font-size: 13px; color: #8BA3C7; line-height: 1.7; margin-bottom: 16px;">By eliminating friction and automating follow-ups, slow response times stop leaking revenue. Your support channel becomes a growth driver.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top">
                  <div style="background: rgba(0,200,160,0.06); border: 1px solid rgba(0,200,160,0.3); border-radius: 12px; overflow: hidden;">
                    <div style="height: 2px; width: 100%; background-color: #00C8A0; background: linear-gradient(90deg, #00C8A0, transparent); border-top-left-radius: 11px; border-top-right-radius: 11px;"></div>
                    <div style="padding: 16px 20px 18px;">
                      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #8BA3C7; margin-bottom: 8px; font-family: 'Courier New', monospace;">Estimated Additional Revenue</div>
                      <div style="font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: #00C8A0;">₹{$revenueUplift}</div>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SCENARIOS (SECTION 06) -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">06 &mdash; Performance Scenarios</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; letter-spacing: -0.3px; color: #E8F0FE;">ROI projections across all outcomes.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top">
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="110" style="font-size: 13px; font-weight: 500; color: #E8F0FE;">Conservative</td>
                        <td>
                          <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div style="height: 6px; background: #5B8DB8; border-radius: 3px; width: 50%;"></div>
                          </div>
                        </td>
                        <td width="70" align="right" style="font-size: 14px; font-weight: 600; font-family: 'Courier New', monospace; color: #5B8DB8;">{$roiConservative}%</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
              <tr><td height="10"></td></tr>
              <tr>
                <td valign="top">
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="110" style="font-size: 13px; font-weight: 500; color: #E8F0FE;">Expected</td>
                        <td>
                          <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div style="height: 6px; background: #0EA5E9; border-radius: 3px; width: 70%;"></div>
                          </div>
                        </td>
                        <td width="70" align="right" style="font-size: 14px; font-weight: 600; font-family: 'Courier New', monospace; color: #0EA5E9;">{$roiExpected}%</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
              <tr><td height="10"></td></tr>
              <tr>
                <td valign="top">
                  <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 14px 18px; overflow: hidden;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="110" style="font-size: 13px; font-weight: 500; color: #E8F0FE;">Aggressive</td>
                        <td>
                          <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px;">
                            <div style="height: 6px; background: #00C8A0; border-radius: 3px; width: 90%;"></div>
                          </div>
                        </td>
                        <td width="70" align="right" style="font-size: 14px; font-weight: 600; font-family: 'Courier New', monospace; color: #00C8A0;">{$roiAggressive}%</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ROADMAP (SECTION 07) -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">07 &mdash; Implementation Roadmap</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; letter-spacing: -0.3px; color: #E8F0FE;">Three phases to full autonomy.</div>
            
            <table width="100%" cellpadding="0" cellspacing="0">
              <!-- Phase 1 -->
              <tr>
                <td width="24" valign="top" style="padding-bottom: 2px;">
                  <div style="width: 24px; height: 24px; background: #0A1628; border: 1.5px solid #00C8A0; color: #00C8A0; border-radius: 12px; text-align: center; line-height: 24px; font-size: 11px; font-weight: 700;">1</div>
                </td>
                <td valign="top" style="padding-left: 16px; padding-bottom: 2px;">
                  <div style="font-size: 14px; font-weight: 700; color: #E8F0FE; margin-bottom: 4px;">Deployment</div>
                </td>
              </tr>
              <tr>
                <td width="24" align="center" valign="top" style="padding-bottom: 2px;">
                  <div style="width: 1.5px; height: 40px; background: rgba(0, 200, 160, 0.3);"></div>
                </td>
                <td valign="top" style="padding-left: 16px; padding-bottom: 25px;">
                  <div style="font-size: 12px; color: #8BA3C7; line-height: 1.5;">Automate high-traffic FAQs and repetitive support flows immediately for fast wins.</div>
                </td>
              </tr>
              
              <!-- Phase 2 -->
              <tr>
                <td width="24" valign="top" style="padding-bottom: 2px;">
                  <div style="width: 24px; height: 24px; background: #0A1628; border: 1.5px solid #00C8A0; color: #00C8A0; border-radius: 12px; text-align: center; line-height: 24px; font-size: 11px; font-weight: 700;">2</div>
                </td>
                <td valign="top" style="padding-left: 16px; padding-bottom: 2px;">
                  <div style="font-size: 14px; font-weight: 700; color: #E8F0FE; margin-bottom: 4px;">CRM Integration</div>
                </td>
              </tr>
              <tr>
                <td width="24" align="center" valign="top" style="padding-bottom: 2px;">
                  <div style="width: 1.5px; height: 40px; background: rgba(0, 200, 160, 0.3);"></div>
                </td>
                <td valign="top" style="padding-left: 16px; padding-bottom: 25px;">
                  <div style="font-size: 12px; color: #8BA3C7; line-height: 1.5;">Sync agents with your CRM to track leads and enable intelligent human routing.</div>
                </td>
              </tr>
              
              <!-- Phase 3 -->
              <tr>
                <td width="24" valign="top" style="padding-bottom: 2px;">
                  <div style="width: 24px; height: 24px; background: #0A1628; border: 1.5px solid #00C8A0; color: #00C8A0; border-radius: 12px; text-align: center; line-height: 24px; font-size: 11px; font-weight: 700;">3</div>
                </td>
                <td valign="top" style="padding-left: 16px; padding-bottom: 2px;">
                  <div style="font-size: 14px; font-weight: 700; color: #E8F0FE; margin-bottom: 4px;">Autonomous Growth</div>
                </td>
              </tr>
              <tr>
                <td width="24" align="center" valign="top">
                </td>
                <td valign="top" style="padding-left: 16px;">
                  <div style="font-size: 12px; color: #8BA3C7; line-height: 1.5;">Deploy proactive engagement campaigns and lead qualification flows at scale.</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- COST OF INACTION (SECTION 08) -->
        <tr class="section">
          <td style="padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
              <tr>
                <td style="font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: #00C8A0; font-family: 'Courier New', monospace; white-space: nowrap; padding-right: 8px; width: 1%;">08 &mdash; The Cost of Inaction</td>
                <td style="width: 99%;"><div style="height: 1px; background-color: rgba(0,200,160,0.2); background: linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%); width: 100%;"></div></td>
              </tr>
            </table>
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 16px; letter-spacing: -0.3px; color: #E8F0FE;">Staying manual is an active choice to bleed efficiency.</div>
            
            <div style="background: rgba(255,80,60,0.06); border: 1px solid rgba(255,80,60,0.2); border-radius: 12px; padding: 20px 24px; overflow: hidden;">
              <div style="font-size: 11px; color: #FF8070; text-transform: uppercase; letter-spacing: 1px; font-family: 'Courier New', monospace; margin-bottom: 6px;">Estimated Yearly Cost of Inefficiency</div>
              <div style="font-size: 24px; font-weight: 700; color: #FF8070; margin-bottom: 8px;">₹{$lostCost}</div>
              <div style="font-size: 13px; color: #C8D8F0; line-height: 1.6;">Delaying automation keeps your costs high and slows your response times &mdash; handing leads directly to faster competitors. Every month you wait compounds the loss.</div>
            </div>
          </td>
        </tr>

        <!-- CTA -->
        <tr class="section">
          <td style="padding: 28px 40px;">
            <div style="background-color: #0d2138; border: 1px solid rgba(0,200,160,0.2); border-radius: 12px; padding: 28px 32px; text-align: center; overflow: hidden;">
              <div style="font-size: 20px; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.3px; color: #E8F0FE;">Ready to evolve?</div>
              <div style="font-size: 13px; color: #8BA3C7; margin-bottom: 20px; line-height: 1.6;">Stop managing dashboards and start building an autonomous operation. Let Cypherox Technologies architect your AI transformation.</div>
              
              <table align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding: 0 6px;">
                    <a href="https://calendly.com/cypheroxtechnologies/15min" target="_blank" style="padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; background: #00C8A0; color: #0A1628; display: inline-block; text-decoration: none;">Book your AI Audit</a>
                  </td>
                  <td style="padding: 0 6px;">
                    <a href="https://www.cypherox.com/get-in-touch" target="_blank" style="padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; background: transparent; color: #00C8A0; border: 1px solid rgba(0,200,160,0.2); display: inline-block; text-decoration: none;">Get your custom Agent roadmap</a>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>

        <!-- FOOTER -->
        <tr class="section">
          <td style="padding: 20px 40px; border-top: 1px solid rgba(255,255,255,0.06);">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left" style="font-size: 11px; color: #8BA3C7; line-height: 1.7; width: 50%;">
                  Disclaimer: Based on industry benchmarks and your inputs. Actual results vary by implementation.
                </td>
                <td align="right" style="width: 50%;">
                  <div style="font-size: 13px; font-weight: 600; color: #00C8A0;">Cypherox Technologies</div>
                  <div style="font-size: 11px; color: #8BA3C7 !important; margin-top: 3px; font-family: 'Courier New', monospace;">
                    <a href="https://cypherox.com" style="color: #8BA3C7; text-decoration: none;">cypherox.com</a> &middot; 
                    <a href="mailto:info@cypherox.com" style="color: #8BA3C7; text-decoration: none;">info@cypherox.com</a> &middot; 
                    <a href="tel:+917227818721" style="color: #8BA3C7; text-decoration: none;">+91 9033199949</a>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
HTML;

$emailMessage = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0A1628; color: #E8F0FE; margin: 0; padding: 0; }
  .container { max-width: 600px; margin: 20px auto; background-color: #0A1628; border: 1px solid rgba(0, 200, 160, 0.2); border-radius: 12px; overflow: hidden; }
  .header { padding: 40px; text-align: center; background: linear-gradient(135deg, #0A1628 0%, #0F2347 100%); border-bottom: 1px solid rgba(0, 200, 160, 0.2); }
  .content { padding: 40px; line-height: 1.8; font-size: 15px; }
  .footer { padding: 30px 40px; background-color: #081220; border-top: 1px solid rgba(255, 255, 255, 0.06); font-size: 13px; color: #8BA3C7; }
  .button { display: inline-block; padding: 14px 28px; background-color: #00C8A0; color: #0A1628; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 25px 0; }
  .highlight { color: #00C8A0; font-weight: 600; }
  .feature-list { list-style: none; padding: 0; margin: 20px 0; }
  .feature-list li { padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0; }
  .feature-list li::before { content: "•"; color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1; }
  .services-list { list-style: none; padding: 0; margin: 20px 0; }
  .services-list li { padding: 4px 0; color: #8BA3C7; font-size: 14px; }
  a { color: #00C8A0; text-decoration: none; }
  .cta-box { background: rgba(0, 200, 160, 0.05); border: 1px dashed rgba(0, 200, 160, 0.3); border-radius: 12px; padding: 25px; margin: 30px 0; text-align: center; }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="https://www.cypherox.com/assets/img/cypherox-logo.png" width="180" alt="Cypherox Technologies">
    </div>
    <div class="content" style="padding: 40px; line-height: 1.8; font-size: 15px; color: #E8F0FE;">
      <h1 style="color: #E8F0FE; font-size: 24px; margin-top: 0; margin-bottom: 20px;">Your Autonomous AI ROI Report is Ready</h1>
      <p style="color: #E8F0FE; margin: 0 0 15px 0;">Hi there,</p>
      <p style="color: #E8F0FE; margin: 0 0 15px 0;">Thank you for exploring Autonomous AI with Cypherox Technologies.</p>
      <p style="color: #E8F0FE; margin: 0 0 15px 0;">Using the operational inputs shared by your team, we’ve prepared a personalized AI Agent ROI Report tailored specifically for <span class="highlight" style="color: #00C8A0; font-weight: 600;">{$companyName}</span> and the <span class="highlight" style="color: #00C8A0; font-weight: 600;">{$industry}</span> sector.</p>
      
      <p style="margin-bottom: 10px; color: #E8F0FE;"><strong>Inside your report, you’ll discover:</strong></p>
      <ul class="feature-list" style="list-style: none; padding: 0; margin: 15px 0;">
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> Estimated annual operational savings</li>
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> ROI & AI investment projections</li>
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> Hours recoverable through intelligent automation</li>
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> Revenue growth opportunities from faster response cycles</li>
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> A phased AI Agent implementation roadmap</li>
        <li style="padding: 8px 0; padding-left: 25px; position: relative; color: #C8D8F0;"><span style="color: #00C8A0; position: absolute; left: 0; font-weight: bold; font-size: 20px; line-height: 1;">•</span> Cost-of-inaction analysis based on your current workflow</li>
      </ul>
      
      <div class="cta-box" style="background: rgba(0, 200, 160, 0.05); border: 1px dashed rgba(0, 200, 160, 0.3); border-radius: 12px; padding: 25px; margin: 30px 0; text-align: center;">
        <p style="margin-top: 0; font-size: 18px; color: #E8F0FE;">✨ <strong>Personalized ROI Report Generated</strong></p>
        <p style="font-size: 14px; color: #8BA3C7; margin-bottom: 20px;">Your full analysis, including the 3-phase roadmap and cost-of-inaction summary, is ready for review.</p>
        <div style="background-color: rgba(0, 200, 160, 0.1); border: 1px solid #00C8A0; color: #00C8A0; padding: 14px 24px; border-radius: 8px; display: inline-block; font-weight: bold;">
          📂 Please find the PDF attached to this email
        </div>
      </div>
      
      <p style="color: #E8F0FE; margin: 0 0 15px 0;">This report is designed to help leadership teams evaluate how Autonomous AI Agents can reduce repetitive workload, improve customer experience, and create scalable operational efficiency across the organization.</p>
      
      <p style="margin-top: 30px; color: #E8F0FE; margin-bottom: 10px;"><strong>At Cypherox Technologies,</strong><br>
      We build enterprise-grade AI solutions that go beyond chatbots, enabling businesses to automate support, lead qualification, internal workflows, and customer engagement with intelligent AI Agents.</p>
      
      <p style="margin-bottom: 10px; margin-top: 25px; color: #E8F0FE;"><strong>If you would like, our AI specialists can also provide:</strong></p>
      <ul class="services-list" style="list-style: none; padding: 0; margin: 15px 0;">
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• AI Agent Development & Automation</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• AI Chatbots & Virtual Assistants</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• AI Integrations & Workflow Solutions</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• Generative AI & Machine Learning Solutions</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• NLP, Predictive Analytics & Intelligent Automation</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• AI App Development & Scalable Infrastructure</li>
        <li style="padding: 4px 0; color: #8BA3C7; font-size: 14px;">• AI Consulting, Optimization & Support</li>
      </ul>
      
      <p style="margin-top: 30px; color: #E8F0FE;">For strategic consultation, you can schedule your consultation here:</p>
      <div style="margin: 20px 0;">
        <a href="https://calendly.com/cypheroxtechnologies/15min" class="button" style="display: inline-block; padding: 14px 28px; background-color: #00C8A0; color: #0A1628; text-decoration: none; border-radius: 8px; font-weight: bold; margin-right: 10px;">📅 Book 15-min Call</a>
        <a href="https://www.cypherox.com/get-in-touch" class="button" style="display: inline-block; padding: 14px 28px; background-color: transparent; border: 1px solid #00C8A0; color: #00C8A0; text-decoration: none; border-radius: 8px; font-weight: bold;">📩 Contact Us</a>
      </div>
    </div>
    <div class="footer" style="padding: 30px 40px; background-color: #081220; border-top: 1px solid rgba(255, 255, 255, 0.06); font-size: 13px; color: #8BA3C7;">
      <p style="margin-top: 0; color: #E8F0FE;">Regards,<br><strong style="color: #E8F0FE;">Team Cypherox Technologies</strong></p>
      <p style="margin-bottom: 0; color: #8BA3C7;">AI • Automation • Digital Transformation<br>
      📧 <a href="mailto:info@cypherox.com" style="color: #00C8A0; text-decoration: none;">info@cypherox.com</a> | 📞 <span style="color: #8BA3C7;">+91 9033199949</span></p>
    </div>
  </div>
</body>
</html>
HTML;


$mail = new PHPMailer(true);

try {
  // Server settings
  // $mail->isSMTP();
  // $mail->Host       = 'smtp.gmail.com';
  // $mail->SMTPAuth   = true;
  // $mail->Username   = 'testerdemo.twenty20@gmail.com';
  // $mail->Password   = 'hnkuqarrephuclvy';
  // $mail->Port       = 587;
  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

  $mail->isSMTP();
  $mail->Host       = $host;
  $mail->SMTPAuth   = true;
  $mail->Username   = $username;
  $mail->Password   = $psw;
  $mail->SMTPSecure = $SMTPSecure;
  $mail->Port       = $port;


  // Recipients
  $mail->setFrom($username, 'ROI Calculator');

  $mail->addAddress($data['email']);
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  // Content
  $mail->isHTML(true);
  $mail->Subject = $companyName . ', Your Autonomous AI ROI Report is Ready';
  $mail->Body    = $emailMessage;

  // Generate PDF
  $options = new Options();
  $options->set('isRemoteEnabled', true);
  $options->set('isHtml5ParserEnabled', true);
  $options->set('defaultFont', 'DejaVu Sans');
  $options->set('defaultMediaType', 'print');
  $options->set('fontDir', sys_get_temp_dir());
  $options->set('fontCache', sys_get_temp_dir());
  $options->set('enable_php', false);
  $dompdf = new Dompdf($options);

  // Add PDF-specific styling to ensure it fits A4 perfectly and handles page breaks correctly
  $pdfStyles = '
    <style>
        @page { 
            margin: 0; 
        }
        html, body { 
            background-color: #0A1628 !important; 
            margin: 0; 
            padding: 0;
        }
        body { 
            padding: 15mm 15mm 25mm 15mm; 
            width: 100%;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .main-wrapper { 
            width: 100% !important; 
            max-width: 100% !important; 
        }
        .section-break { 
            page-break-before: always; 
        }
        /* Dompdf table break fixes */
        table { page-break-inside: auto; }
        tr { page-break-inside: auto; }
        td { page-break-inside: auto; }
        
        /* Pagination placeholder (handled via Canvas for better compatibility) */
        
        /* Fix roadmap circles for PDF */
        .roadmap-circle-patch { 
            border-radius: 50% !important; 
            text-align: center !important; 
            line-height: 22px !important; 
            display: block !important; 
        }
        
        /* PDF-specific fix for roadmap section numbers alignment */
        td[width="24"] div[style*="border: 1.5px solid"] {
            display: inline-block !important;
            width: 24px !important;
            height: 24px !important;
            line-height: 24px !important;
            text-align: center !important;
            vertical-align: middle !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 50% !important;
            flex-shrink: 0 !important;
        }
        
        /* Ensure vertical lines dont cause wrapping */
        td[width="24"] div[style*="width: 1.5px"] {
            display: block !important;
            margin: 0 auto !important;
            flex-shrink: 0 !important;
        }
        
        /* Section 04 numbered circles fix for PDF */
        .section-04-circle {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 24px !important;
            height: 24px !important;
            line-height: 24px !important;
            text-align: center !important;
            vertical-align: middle !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 50% !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
        }
    </style>';

  // 1. Setup PDF Message from Original Template
  $pdfMessage = str_replace('</head>', $pdfStyles . '</head>', $pdfTemplate);
  $pdfMessage = str_replace('margin: 0 auto;', 'margin: 0;', $pdfMessage);

  // Add charset declaration to HTML for proper rupee sign rendering
  $pdfMessage = str_replace('</html>', '</html>', $pdfMessage);
  if (strpos($pdfMessage, '<meta charset') === false && strpos($pdfMessage, 'charset=utf-8') === false) {
    $pdfMessage = str_replace('<head>', '<head><meta charset="UTF-8">', $pdfMessage);
  }

  // 2. Handle Page Breaks (Using regex to handle different newline formats \n or \r\n)
  // Page 2: Start with Section 06 (06, 07, and 08 will stay together)
  $pdfMessage = preg_replace('/<!-- SCENARIOS \(SECTION 06\) -->\s*<tr class="section">/', '<!-- SCENARIOS (SECTION 06) --><tr class="section section-break">', $pdfMessage);

  // Page 3: Start with CTA (Footer stays with CTA)
  $pdfMessage = preg_replace('/<!-- CTA -->\s*<tr class="section">/', '<!-- CTA --><tr class="section section-break">', $pdfMessage);

  // 3. Patch Styling for PDF Engine (Roadmap Circles & Lines)
  $pdfMessage = str_replace('border-radius: 12px; text-align: center; line-height: 24px;', 'border-radius: 50% !important; text-align: center !important; line-height: 22px !important; display: block !important;', $pdfMessage);
  $pdfMessage = str_replace('width: 1.5px; height: 40px; background: rgba(0, 200, 160, 0.3);', 'width: 1.5px; height: 40px; background: rgba(0, 200, 160, 0.3); margin: 0 auto;', $pdfMessage);

  // 4. Global PDF Adjustments (Scaling, Currency, Gradients)
  $pdfMessage = str_replace('width="800"', 'width="100%"', $pdfMessage);
  $pdfMessage = str_replace(
    ['linear-gradient(to right, rgba(0,200,160,0.4) 0%, transparent 100%)', 'linear-gradient(90deg, #00C8A0, transparent)'],
    ['rgba(0,200,160,0.4)', '#00C8A0'],
    $pdfMessage
  );

  $dompdf->loadHtml($pdfMessage);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  // Add Pagination using Canvas (Most reliable method for Dompdf to avoid layout/blank page issues)
  $canvas = $dompdf->getCanvas();
  $font = $dompdf->getFontMetrics()->get_font("DejaVu Sans", "normal");
  $size = 10;
  $color = array(0 / 255, 200 / 255, 160 / 255); // Teal #00C8A0
  $canvas->page_text(260, 810, "Report Page {PAGE_NUM}", $font, $size, $color);

  // Get PDF output and attach
  $pdfOutput = $dompdf->output();
  $mail->addStringAttachment($pdfOutput, "ROI_Report_{$companyName}.pdf");

  $mail->send();
  echo json_encode(['success' => true, 'message' => 'Email sent successfully!']);
} catch (Exception $e) {
  //print_r($e);
  // Log the error to a file for debugging
  $logMsg = "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . " | File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
  error_log($logMsg, 3, "error_log.txt");

  echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
}
