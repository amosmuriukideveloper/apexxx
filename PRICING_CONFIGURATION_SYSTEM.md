# 💰 Project Pricing Configuration System

## ✅ Complete Implementation!

This document explains the dynamic pricing configuration system that allows admins to manage all calculator parameters without touching code.

---

## 🎯 Purpose

Centralized management of:
- Base rates for all project types
- Difficulty level multipliers
- Urgency/rush fee multipliers
- Currency settings
- Rounding preferences
- Platform commission
- Tax configuration

---

## 📊 Settings Structure

### Settings Class

**File**: `app/Settings/ProjectPricingSettings.php`

**Properties**:
```php
├─ base_rates (array)                    // Project type → rate mapping
├─ easy_multiplier (float)               // Difficulty multipliers
├─ medium_multiplier (float)
├─ hard_multiplier (float)
├─ normal_urgency_multiplier (float)     // Urgency multipliers
├─ urgent_multiplier (float)
├─ super_urgent_multiplier (float)
├─ currency_code (string)                // Currency settings
├─ currency_symbol (string)
├─ currency_position (string)
├─ decimal_places (int)                  // Formatting
├─ rounding_mode (string)
├─ platform_commission_percentage (float) // Business rules
├─ minimum_project_cost (float)
├─ maximum_project_cost (float)
├─ tax_enabled (bool)                    // Tax settings
└─ tax_percentage (float)
```

---

## 🎨 Settings Page

### Navigation

**Location**: Settings → Pricing & Calculator
**Icon**: Calculator
**Sort**: 6

### Tab Structure

```
Pricing Configuration Tabs:
├─ 1. Base Rates (💵)
├─ 2. Multipliers (×)
├─ 3. Currency & Formatting ($)
├─ 4. Business Rules (⚙️)
└─ 5. Calculator Preview (🧮)
```

---

## 💵 Tab 1: Base Rates

### Purpose
Configure the base rate per page for each project type.

### Features

**KeyValue Input**:
```
Project Type        | Rate per Page ($)
--------------------+------------------
essay               | 10.00
research_paper      | 15.00
dissertation        | 25.00
thesis              | 20.00
case_study          | 12.00
lab_report          | 11.00
presentation        | 8.00
assignment          | 9.00
coursework          | 10.00
article             | 13.00
coding_project      | 20.00
data_analysis       | 18.00
```

**Add/Remove**: Fully dynamic
**Reorder**: Yes
**Default Values**: Pre-configured

### Suggested Rates Display
```
Essay: $10 | Research Paper: $15 | Dissertation: $25 | 
Thesis: $20 | Case Study: $12 | Lab Report: $11 | 
Presentation: $8 | Assignment: $9 | Coursework: $10 | 
Article: $13 | Coding: $20
```

---

## ✖️ Tab 2: Multipliers

### Difficulty Level Multipliers

**Purpose**: Adjust pricing based on complexity

| Level | Default | Range | Description |
|-------|---------|-------|-------------|
| **Easy** | ×1.0 | 0.5-3.0 | No additional cost |
| **Medium** | ×1.3 | 0.5-3.0 | 30% increase (recommended: 1.3-1.5) |
| **Hard** | ×1.6 | 0.5-3.0 | 60% increase (recommended: 1.6-2.0) |

**Step**: 0.1 increments
**Prefix**: × symbol

---

### Urgency Level Multipliers

**Purpose**: Rush fee calculation

| Level | Default | Range | Description |
|-------|---------|-------|-------------|
| **Normal (7+ days)** | ×1.0 | 0.5-3.0 | No rush fee |
| **Urgent (3-7 days)** | ×1.5 | 0.5-3.0 | 50% rush fee |
| **Super Urgent (<3 days)** | ×2.0 | 0.5-3.0 | 100% rush fee |

**Step**: 0.1 increments
**Prefix**: × symbol

---

### Multiplier Preview

**Live Example Calculations**:
```
Essay (10 pages, $10 base):
• Easy + Normal: $100.00
• Medium + Urgent: $195.00  
• Hard + Super Urgent: $320.00
```

Updates in real-time as multipliers change!

---

## 💱 Tab 3: Currency & Formatting

### Currency Settings

**Currency Code**:
```
Options:
├─ USD - US Dollar
├─ EUR - Euro
├─ GBP - British Pound
├─ KES - Kenyan Shilling
├─ ZAR - South African Rand
├─ NGN - Nigerian Naira
├─ CAD - Canadian Dollar
└─ AUD - Australian Dollar
```

**Currency Symbol**: Custom input (e.g., $, €, £, KSh)

**Symbol Position**:
- Before Amount: $100
- After Amount: 100$

---

### Number Formatting

**Decimal Places**:
- Range: 0-4
- Default: 2
- Example: 2 = $10.00

**Rounding Mode**:
```
├─ Round Up: Always higher (e.g., $10.56 → $10.60)
├─ Round Down: Always lower (e.g., $10.56 → $10.50)
└─ Round to Nearest: Standard (e.g., $10.56 → $10.56)
```

**Format Preview**:
Shows live preview: `$123.46` (updates as you change settings)

---

## ⚙️ Tab 4: Business Rules

### Platform Commission

**Purpose**: Fee taken by platform from project cost

**Configuration**:
- Range: 0-100%
- Default: 20%
- Step: 0.5%

**Example Display**:
```
Project Cost: $100
Platform Fee (20%): $20
Expert Earnings: $80
```

Updates in real-time!

---

### Pricing Limits

**Minimum Project Cost**:
- Default: $10.00
- Purpose: Smallest allowed project amount
- Validation: Auto-enforced

**Maximum Project Cost**:
- Default: $10,000.00
- Purpose: Largest allowed project amount
- Validation: Auto-enforced

---

### Tax Configuration

**Enable Tax/VAT**: Toggle ON/OFF

**Tax Percentage** (when enabled):
- Range: 0-100%
- Step: 0.1%
- Purpose: Add VAT/Sales tax to projects

**Example**:
```
Base Cost: $100
Tax (15%): $15
Total: $115
```

---

## 🧮 Tab 5: Calculator Preview

### Live Testing

**Purpose**: Test pricing configuration in real-time

**Sample Calculation Display**:
```
Essay (10 pages @ $10/page):

Easy + Normal:
  Cost: $100.00
  Platform: $20.00
  Expert: $80.00

Medium + Urgent:
  Cost: $195.00
  Platform: $39.00
  Expert: $156.00

Hard + Super Urgent:
  Cost: $320.00
  Platform: $64.00
  Expert: $256.00
```

**Updates live** as you change any setting!

Shows:
- Total project cost
- Platform commission
- Expert earnings
- All with configured currency formatting

---

## 🔢 Calculation Formula

### Complete Formula

```
Total Cost = (Base Rate × Difficulty × Urgency × Pages)
           + Tax (if enabled)

Platform Fee = Total Cost × (Commission % / 100)
Expert Earnings = Total Cost - Platform Fee
```

### Step-by-Step Example

```
Project: Research Paper
Pages: 20
Difficulty: Medium
Urgency: Urgent
Settings:
  - Base Rate: $15/page
  - Medium Multiplier: ×1.3
  - Urgent Multiplier: ×1.5
  - Tax: 10%
  - Commission: 20%

Calculation:
1. Base Cost = 15 × 1.3 × 1.5 × 20 = $585
2. Tax = 585 × 0.10 = $58.50
3. Total = 585 + 58.50 = $643.50
4. Platform = 643.50 × 0.20 = $128.70
5. Expert = 643.50 - 128.70 = $514.80

Final:
  Student Pays: $643.50
  Platform Gets: $128.70
  Expert Gets: $514.80
```

---

## 🎯 Usage in Project Forms

### Integration

```php
use App\Settings\ProjectPricingSettings;

// In form
Forms\Components\Placeholder::make('estimated_cost')
    ->content(function ($get) {
        $settings = app(ProjectPricingSettings::class);
        
        $type = $get('project_type');
        $difficulty = $get('difficulty_level');
        $urgency = $get('urgency');
        $pages = (int) ($get('page_count') ?? 1);
        
        // Get base rate from settings
        $baseRate = $settings->base_rates[$type] ?? 10;
        
        // Get multipliers from settings
        $diffMult = match($difficulty) {
            'easy' => $settings->easy_multiplier,
            'medium' => $settings->medium_multiplier,
            'hard' => $settings->hard_multiplier,
            default => 1.0,
        };
        
        $urgMult = match($urgency) {
            'normal' => $settings->normal_urgency_multiplier,
            'urgent' => $settings->urgent_multiplier,
            'super_urgent' => $settings->super_urgent_multiplier,
            default => 1.0,
        };
        
        // Calculate
        $subtotal = $baseRate * $diffMult * $urgMult * $pages;
        
        // Add tax if enabled
        if ($settings->tax_enabled) {
            $tax = $subtotal * ($settings->tax_percentage / 100);
            $subtotal += $tax;
        }
        
        // Format with currency settings
        $amount = number_format($subtotal, $settings->decimal_places);
        
        return $settings->currency_position === 'before'
            ? $settings->currency_symbol . $amount
            : $amount . $settings->currency_symbol;
    }),
```

---

## 📊 Default Configuration

### Out-of-the-Box Values

```php
Base Rates:
├─ Essay: $10/page
├─ Research Paper: $15/page
├─ Dissertation: $25/page
├─ Thesis: $20/page
├─ Case Study: $12/page
├─ Lab Report: $11/page
├─ Presentation: $8/page
├─ Assignment: $9/page
├─ Coursework: $10/page
├─ Article: $13/page
├─ Coding Project: $20/page
└─ Data Analysis: $18/page

Difficulty Multipliers:
├─ Easy: ×1.0 (No increase)
├─ Medium: ×1.3 (30% increase)
└─ Hard: ×1.6 (60% increase)

Urgency Multipliers:
├─ Normal: ×1.0 (No rush fee)
├─ Urgent: ×1.5 (50% rush fee)
└─ Super Urgent: ×2.0 (100% rush fee)

Currency: USD ($)
Decimals: 2
Rounding: Nearest
Commission: 20%
Min Cost: $10
Max Cost: $10,000
Tax: Disabled (0%)
```

---

## 🔄 Updating Settings

### Via Admin Panel

```
1. Login as admin
2. Go to: Settings → Pricing & Calculator
3. Navigate to appropriate tab
4. Modify values
5. Click "Save"
6. Settings applied immediately
```

### Via Code (Optional)

```php
use App\Settings\ProjectPricingSettings;

$settings = app(ProjectPricingSettings::class);

// Update base rate
$baseRates = $settings->base_rates;
$baseRates['custom_type'] = 17.50;
$settings->base_rates = $baseRates;

// Update multipliers
$settings->medium_multiplier = 1.4;
$settings->urgent_multiplier = 1.6;

// Update currency
$settings->currency_code = 'EUR';
$settings->currency_symbol = '€';

$settings->save();
```

---

## 🧪 Testing

### Test Scenarios

**Scenario 1: Change Base Rate**
```
1. Go to Base Rates tab
2. Change Essay rate from $10 to $12
3. Go to Calculator Preview
4. Verify calculations updated
5. Create test project
6. Verify cost uses new rate
```

**Scenario 2: Adjust Multipliers**
```
1. Go to Multipliers tab
2. Change Medium from ×1.3 to ×1.5
3. View preview calculations
4. Verify 50% increase shown
5. Test in project form
```

**Scenario 3: Currency Change**
```
1. Go to Currency & Formatting
2. Change to GBP (£)
3. Change position to after
4. View preview: 100£
5. Check project forms
6. Verify all prices use £
```

**Scenario 4: Commission Change**
```
1. Go to Business Rules
2. Change commission from 20% to 25%
3. View example calculation
4. Verify expert earnings reduced
5. Check calculator preview
```

---

## 🎨 UI Features

### Real-Time Updates

- All preview sections update live
- No need to save to see changes
- Instant feedback on modifications

### Helper Text

Every field has:
- Description of purpose
- Recommended values
- Example calculations

### Validation

- Range limits enforced
- Required fields marked
- Invalid inputs prevented

### Visual Organization

- Grouped by category
- Collapsible sections
- Color-coded tabs
- Clear labels

---

## 🔐 Security

### Access Control

Only users with admin permissions can:
- View pricing settings
- Modify rates
- Change multipliers
- Update currency settings

### Audit Trail

All changes logged with:
- Who made the change
- When it was changed
- What was changed
- Old vs new values

---

## 📋 Maintenance

### Regular Reviews

Recommended schedule:
- **Weekly**: Check calculator preview
- **Monthly**: Review base rates vs market
- **Quarterly**: Adjust multipliers
- **Yearly**: Currency/tax review

### Best Practices

1. **Test Before Deploy**: Use preview tab
2. **Gradual Changes**: Small increments
3. **Monitor Impact**: Track project prices
4. **Get Feedback**: Ask experts and students
5. **Document Changes**: Note why adjusted

---

## 🎯 Summary

**What's Built**:
- ✅ Dynamic base rate management
- ✅ Configurable multipliers
- ✅ Multi-currency support
- ✅ Flexible formatting options
- ✅ Platform commission settings
- ✅ Tax configuration
- ✅ Live calculator preview
- ✅ Real-time validation

**Benefits**:
- ✅ No code changes needed
- ✅ Instant updates
- ✅ Complete control
- ✅ Easy testing
- ✅ Professional UI

**Files Created**:
- ✅ `app/Settings/ProjectPricingSettings.php`
- ✅ `app/Filament/Pages/ManageProjectPricingSettings.php`

---

**Complete pricing configuration system with live preview! 💰**
