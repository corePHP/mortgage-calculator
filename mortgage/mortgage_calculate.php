<?php
/**
* @version $Id: core_mortgage.php
* @package CorePHP.com Mortgage Component
* @copyright Copyright (C) 2006 Steven Pignataro and Jonathan Shroyer. All rights reserved.
*
* Steven Pignataro
* Mirror Image Productions: http://www.themirrorimages.com
* CorePHP: http://www.corephp.com
*
* Jonathan Shroyer
* Design Innovations: http://www.designinnovations.net
*/
class mortgageCal{
	
	function displayMortgageResults(){
		
    $default_sale_price              = "150000";
    $default_annual_interest_percent = 7.0;
    $default_year_term               = 30;
    $default_down_percent            = 10;
    $default_show_progress           = TRUE;
    $sale_price                      = 0;
    $annual_interest_percent         = 0;
    $year_term                       = 0;
    $down_percent                    = 0;
    $this_year_interest_paid         = 0;
    $this_year_principal_paid        = 0;
    $form_complete                   = false;
    $show_progress                   = false;
    $monthly_payment                 = false;
    $show_progress                   = false;
    $error                           = false;
    if (isset($_REQUEST['form_complete'])) {
        $sale_price                      = $_REQUEST['sale_price'];
        $annual_interest_percent         = $_REQUEST['annual_interest_percent'];
        $year_term                       = $_REQUEST['year_term'];
        $down_percent                    = $_REQUEST['down_percent'];
        $show_progress                   = (isset($_REQUEST['show_progress'])) ? $_REQUEST['show_progress'] : false;
        $form_complete                   = $_REQUEST['form_complete'];
		
    }
    function get_interest_factor($year_term, $monthly_interest_rate) {
        global $base_rate;
        
        $factor      = 0;
        $base_rate   = 1 + $monthly_interest_rate;
        $denominator = $base_rate;
        for ($i=0; $i < ($year_term * 12); $i++) {
            $factor += (1 / $denominator);
            $denominator *= $base_rate;
        }
        return $factor;
    }        
       //Start Math
    if ($form_complete) {
        $sale_price              = ereg_replace( "[^0-9.]", "", $sale_price);
        $annual_interest_percent = eregi_replace("[^0-9.]", "", $annual_interest_percent);
        $year_term               = eregi_replace("[^0-9.]", "", $year_term);
        $down_percent            = eregi_replace("[^0-9.]", "", $down_percent);
        
        if (((float) $year_term <= 0) || ((float) $sale_price <= 0) || ((float) $annual_interest_percent <= 0)) {
            $error = "You must enter a <strong>Sale Price of Home</strong>, <strong>Length of Motgage</strong> <em>and</em> <strong>Annual Interest Rate</strong>";
        }
        
        if (!$error) {
            $month_term              = $year_term * 12;
            $down_payment            = $sale_price * ($down_percent / 100);
            $annual_interest_rate    = $annual_interest_percent / 100;
            $monthly_interest_rate   = $annual_interest_rate / 12;
            $financing_price         = $sale_price - $down_payment;
            $monthly_factor          = get_interest_factor($year_term, $monthly_interest_rate);
            $monthly_payment         = $financing_price / $monthly_factor;
        }
    } else {
        if (!$sale_price)              { $sale_price              = $default_sale_price;              }
        if (!$annual_interest_percent) { $annual_interest_percent = $default_annual_interest_percent; }
        if (!$year_term)               { $year_term               = $default_year_term;               }
        if (!$down_percent)            { $down_percent            = $default_down_percent;            }
        if (!$show_progress)           { $show_progress           = $default_show_progress;           }
    }
    
    if ($error) {
        print("<font color=\"red\">" . $error . "</font><br /><br />\n");
        $form_complete   = false;
    }
?>
<!-- Will be implemented later for accessiblity -->
<style type="text/css" media="all"> 
@import "/components/com_mortgage/mortgage.css";
</style>
<div id="mortgage-calculator">
<h3>Mortgage and Amortization Calculator</h3>
<p>This <strong>mortgage calculator</strong> can be used to figure out monthly payments of a home mortgage loan, based on the home's sale price, the term of the loan desired, buyer's down payment percentage, and the loan's interest rate. This calculator factors in <abbr title="private mortgage insurance">PMI</abbr> (Private Mortgage Insurance) for loans where less than 20% is put as a down payment. Also taken into consideration are the town property taxes, and their effect on the total monthly mortgage payment.</p>

<form method="POST" id="information" name="information" action="<?php echo JURI::base().'index.php?option=com_mortgage'; ?>">
<input type="hidden" name="form_complete" value="1">
	<fieldset>
    <legend><strong>Purchase &amp; Financing Information</strong></legend>
        <label for="sale_price">Sale Price of Home: &nbsp;</label>
		<div><input title="sale price of home" class="input" type="text" maxlength="10" size="10" name="sale_price" id="sale_price" value="<?php echo $sale_price; ?>" />(In Dollars)</div><br />
		
		<label for="down_percent">Percentage Down: &nbsp;</label>
		<div><input title="percent down" class="input" type="text" maxlength="5" size="5" name="down_percent" id="down_percent" value="<?php echo $down_percent; ?>" />%</div><br />
		
		<label for="year_term">Length of Mortgage: &nbsp;</label>
		<div><input title="percent down" class="input" type="text" maxlength="3" size="3" name="year_term" id="year_term" value="<?php echo $year_term; ?>" />years</div><br />
		
		<label for="annual_interest_percent">Annual Interest Rate: &nbsp;</label>
		<div><input title="annual interest rate" class="input" type="text" maxlength="5" size="5" name="annual_interest_percent" id="annual_interest_percent" value="<?php echo $annual_interest_percent; ?>" />%</div><br />
		
		<label for="show_progress">Explain Calculations:&nbsp;</label>
		<div><input title="explain calculations" class="input" type="checkbox" name="show_progress" id="show_progress" value="1" <?php if ($show_progress) { print("checked"); } ?> /> Show me the calculations and amortization</div><br />

        <input type="submit" name="Calculate" value="Calculate"><br />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php if ($form_complete) { print("<a href=\"" .JURI::base().'index.php?option=com_mortgage/'. "\"><br />Start Over</a><br />"); } ?><br />
	</fieldset>

<?php
    if ($form_complete && $monthly_payment) {
?>
	<h3>Mortgage Payment Information</h3>
	<div class="mortgage-totals">
		<h4>Down Payment:</h4>
		<div><strong><?php echo "\$" . number_format($down_payment, "2", ".", ","); ?></strong></div><br />

		<h4>Amount Financed:</h4>
		<div><strong><?php echo "\$" . number_format($financing_price, "2", ".", ","); ?></strong></div><br />

		<h4>Monthly Payment:</h4>
		<div><strong><?php echo "\$" . number_format($monthly_payment, "2", ".", ","); ?></strong><br /><sub>(Principal &amp; Interest ONLY)</sub></div>
	</div>
			
	<?php
		if ($down_percent < 20) {
			$pmi_per_month = 55 * ($financing_price / 100000);
	?>

	<p>Since you are putting LESS than 20% down, you will need to pay <abbr title="private mortgage insurance">PMI</abbr> (<a href="http://www.google.com/search?hl=en&q=private+mortgage+insurance">Private Mortgage Insurance</a>), which tends to be about $55 per month for every $100,000 financed (until you have paid off 20% of your loan). This could add <?php echo "\$" . number_format($pmi_per_month, "2", ".", ","); ?> to your monthly payment.</p>
	<div class="mortgage-totals">
		<h4>Monthly Payment: </h4>
		<div><strong><?php echo "\$" . number_format(($monthly_payment + $pmi_per_month), "2", ".", ","); ?></strong><br /><sub>(Principal &amp; Interest, and <abbr title="private mortgage insurance">PMI</abbr>)</span></sub></div>
	</div>

	<?php
		}
	?>
	<div>
		<?php
			$assessed_price          = ($sale_price * .85);
			$residential_yearly_tax  = ($assessed_price / 1000) * 14;
			$residential_monthly_tax = $residential_yearly_tax / 12;
			
			if ($pmi_per_month) {
				$pmi_text = "PMI and ";
			}
		?>
		<p>Residential (or Property) Taxes are a little harder to figure out... In Massachusetts, the average resedential tax rate seems to be around $14 per year for every $1,000 of your property's assessed value.</p>
		<p>Let's say that your property's <em>assessed value</em> is 85% of what you actually paid for it - <?php echo "\$" . number_format($assessed_price, "2", ".", ","); ?>. This would mean that your yearly residential taxes will be around <?php echo "\$" . number_format($residential_yearly_tax, "2", ".", ","); ?>
		This could add <?php echo "\$" . number_format($residential_monthly_tax, "2", ".", ","); ?> to your monthly payment.</p>
	</div>
	<div class="mortgage-totals">
		<h4>TOTAL Monthly Payment:</h4>
		<div><strong><?php echo "\$" . number_format(($monthly_payment + $pmi_per_month + $residential_monthly_tax), "2", ".", ","); ?></strong><br /><sub>(including <?php echo $pmi_text; ?> residential tax)</sub></div>
	</div>
<?php    
    }
?>
</form>

<?php
    if ($form_complete && $show_progress) {
        $step = 1;
?>
	<br />

	<table summary="Payment summary and explanation">
		<caption>Table: Payment summary and explanation of the formulas used to come up with payment chart.</caption>
		<tr>
			<td><strong><?php echo $step++; ?></strong></td>
			<td class="mort_summary">
				The <strong>down payment</strong> = The price of the home multiplied by the percentage down divided by 100 (for 5% down becomes 5/100 or 0.05)<br /><br />
				$<?php echo number_format($down_payment,"2",".",","); ?> = $<?php echo number_format($sale_price,"2",".",","); ?> X (<?php echo $down_percent; ?> / 100)
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $step++; ?></strong></td>
			<td>
				The <strong>interest rate</strong> = The annual interest percentage divided by 100<br /><br />
				<?php echo $annual_interest_rate; ?> = <?php echo $annual_interest_percent; ?>% / 100
			</td>
		</tr>
		<tr>
			<td colspan="2" class="mort_summary">
				The <strong>monthly factor</strong> = The result of the following formula:
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $step++; ?></strong></td>
			<td>
				The <strong>monthly interest rate</strong> = The annual interest rate divided by 12 (for the 12 months in a year)<br /><br />
				<?php echo $monthly_interest_rate; ?> = <?php echo $annual_interest_rate; ?> / 12
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $step++; ?></strong></td>
			<td class="mort_summary">
				The <strong>month term</strong> of the loan in months = The number of years you've taken the loan out for times 12<br /><br />
				<?php echo $month_term; ?> Months = <?php echo $year_term; ?> Years X 12
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $step++; ?></strong></td>
			<td>
				The montly payment is figured out using the following formula:<br />
				Monthly Payment = <?php echo number_format($financing_price, "2", "", ""); ?> * (<?php echo number_format($monthly_interest_rate, "4", "", ""); ?> / (1 - ((1 + <?php echo number_format($monthly_interest_rate, "4", "", ""); ?>)<sup>-(<?php echo $month_term; ?>)</sup>)))
				<br /><br />
				The <a href="#amortization">amortization</a> breaks down how much of your monthly payment goes towards the bank's interest, and how much goes into paying off the principal of your loan.
			</td>
		</tr>
	</table>
	<br />
<!-- Table of Amortization -->
<?php
        $principal     = $financing_price;
        $current_month = 1;
        $current_year  = 1;
        $power = -($month_term);
        $denom = pow((1 + $monthly_interest_rate), $power);
        $monthly_payment = $principal * ($monthly_interest_rate / (1 - $denom));
        
        print("<br /><p><a name=\"amortization\"></a>Amortization For Monthly Payment: <strong>\$" . number_format($monthly_payment, "2", ".", ",") . "</strong> over " . $year_term . " years.</p>\n");
        
        //Reprinted every 12 months
		$legend  = "\t<thead><tr>\n";
        $legend .= "\t\t<th scope=\"col\" abbr=\"Month\"><strong>Month</strong></td>\n";
        $legend .= "\t\t<th scope=\"col\" abbr=\"Interest\"><strong>Interest Paid</strong></td>\n";
        $legend .= "\t\t<th scope=\"col\" abbr=\"Principal\"><strong>Principal Paid</strong></td>\n";
        $legend .= "\t\t<th scope=\"col\" abbr=\"Balance\"><strong>Remaing Balance</strong></td>\n";
        $legend .= "\t</tr></thead>\n";
		$legend .= "\t<tbody>\n";
        
		print("\t<table summary=\"An amortization break down of your first year of how much of your monthly payment goes towards the bank's interest, and how much goes into paying off the principal of your loan.\" class=\"amor-data_table first_table\">\n");
		print("\t<caption>Table: Amortization of monthly payments in the first year.</caption>\n");
		
		echo $legend;
		     
        while ($current_month <= $month_term) {        
            $interest_paid     = $principal * $monthly_interest_rate;
            $principal_paid    = $monthly_payment - $interest_paid;
            $remaining_balance = $principal - $principal_paid;
            
            $this_year_interest_paid  = $this_year_interest_paid + $interest_paid;
            $this_year_principal_paid = $this_year_principal_paid + $principal_paid;
            
            print("\t<tr>\n");
            print("\t\t<th scope=\"row\" class=\"column-month\">" . $current_month . "</th>\n");
            print("\t\t<td class=\"column-interest\">\$" . number_format($interest_paid, "2", ".", ",") . "</td>\n");
            print("\t\t<td class=\"column-principal\">\$" . number_format($principal_paid, "2", ".", ",") . "</td>\n");
            print("\t\t<td class=\"column-balance\">\$" . number_format($remaining_balance, "2", ".", ",") . "</td>\n");
            print("\t</tr>\n");
    
            ($current_month % 12) ? $show_legend = FALSE : $show_legend = TRUE;
    
            if ($show_legend) {
                print("\t</tbody>\n");
				print("\t<tfoot>\n");
				print("\t<tr class=\"mortgage-amort-bottom\">\n");
                print("\t\t<td colspan=\"4\"><strong>Totals for year " . $current_year . "</td>\n");
                print("\t</tr>\n");
                
                $total_spent_this_year = $this_year_interest_paid + $this_year_principal_paid;
				print("\t<tr>\n");
                print("\t\t<td colspan=\"4\" class=\"mort_summary\">\n");
                print("\t\t\tYou will spend \$" . number_format($total_spent_this_year, "2", ".", ",") . " on your house in year " . $current_year . "<br />\n");
                print("\t\t\t\$" . number_format($this_year_interest_paid, "2", ".", ",") . " will go towards INTEREST<br />\n");
                print("\t\t\t\$" . number_format($this_year_principal_paid, "2", ".", ",") . " will go towards PRINCIPAL<br />\n");
                print("\t\t</td>\n");
                print("\t</tr>\n");
				print("\t</tfoot>\n");
				print("\t</table>\n");
				print("\t<table summary=\"An amortization break down for year ". ($current_year + 1) ." of how much of your monthly payment goes towards the bank's interest, and how much goes into paying off the principal of your loan.\" class=\"amor-data_table\">\n");
				print("\t<caption>Table: Amortization of monthly payments for year ". ($current_year + 1) ."</caption>\n");
                
                $current_year++;
                $this_year_interest_paid  = 0;
                $this_year_principal_paid = 0;
                
                if (($current_month + 6) < $month_term) {
                    echo $legend;
                }
            }
    		
            $principal = $remaining_balance;
            $current_month++;
        }
		print("\t<tr>\n");
		print("\t\t<td><strong>End of Mortgage Payments</strong></td>\n");
		print("\t</tr>\n");
        print("</table>\n");
    }
	}
}
?>
<!-- end Table of Amortization -->
</div><!-- end #mortgage-calculator -->