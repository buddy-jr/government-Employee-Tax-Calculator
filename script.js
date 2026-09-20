document
    .getElementById("taxForm")
    .addEventListener("submit", function(event) {

        event.preventDefault();


        // =========================================
        // GET USER INPUT
        // =========================================

        const name =
            document
                .getElementById("employee_name")
                .value
                .trim();


        const salary =
            parseFloat(
                document
                    .getElementById("monthly_salary")
                    .value
            );


        const otherIncome =
            parseFloat(
                document
                    .getElementById("other_income")
                    .value
            ) || 0;


        const months =
            parseInt(
                document
                    .getElementById("months_worked")
                    .value
            );


        // =========================================
        // VALIDATION
        // =========================================

        if (name === "") {

            alert("Please enter the employee name.");

            return;
        }


        if (isNaN(salary) || salary <= 0) {

            alert("Please enter a valid monthly salary.");

            return;
        }


        if (
            isNaN(months) ||
            months < 1 ||
            months > 12
        ) {

            alert(
                "Number of months worked must be between 1 and 12."
            );

            return;
        }


        // =========================================
        // JAVASCRIPT CALCULATION
        // =========================================

        const annualGrossIncome =
            (salary * months) + otherIncome;


        // Employee share
        // 5% total × 50% employee
        const philhealth =
            annualGrossIncome * 0.025;


        const taxableIncome =
            annualGrossIncome - philhealth;


        let incomeTax = 0;


        if (taxableIncome <= 250000) {

            incomeTax = 0;

        }

        else if (taxableIncome <= 400000) {

            incomeTax =
                (taxableIncome - 250000) * 0.15;

        }

        else if (taxableIncome <= 800000) {

            incomeTax =
                22500 +
                ((taxableIncome - 400000) * 0.20);

        }

        else if (taxableIncome <= 2000000) {

            incomeTax =
                102500 +
                ((taxableIncome - 800000) * 0.25);

        }

        else if (taxableIncome <= 8000000) {

            incomeTax =
                402500 +
                ((taxableIncome - 2000000) * 0.30);

        }

        else {

            incomeTax =
                2202500 +
                ((taxableIncome - 8000000) * 0.35);
        }


        const totalDeductions =
            philhealth + incomeTax;


        const netAnnualIncome =
            annualGrossIncome - totalDeductions;


        // =========================================
        // SEND SAME INPUT TO PHP
        // =========================================

        const formData =
            new FormData();


        formData.append(
            "employee_name",
            name
        );

        formData.append(
            "monthly_salary",
            salary
        );

        formData.append(
            "other_income",
            otherIncome
        );

        formData.append(
            "months_worked",
            months
        );


        // =========================================
        // BUTTON
        // =========================================

        const button =
            document.getElementById("computeButton");


        button.disabled = true;

        button.innerHTML =
            "Computing...";


        // =========================================
        // SEND TO PHP
        // =========================================

        fetch("calculator.php", {

            method: "POST",

            body: formData

        })

        .then(response => response.json())

        .then(phpResult => {


            button.disabled = false;

            button.innerHTML =
                "<span>🧮</span> Compute";


            if (!phpResult.success) {

                alert(phpResult.message);

                return;
            }


            // =====================================
            // COMPARE JAVASCRIPT AND PHP
            // =====================================

            const jsTotal =
                totalDeductions.toFixed(2);

            const phpTotal =
                Number(
                    phpResult.totalDeductions
                ).toFixed(2);


            /*
             * Both PHP and JavaScript processed
             * the same input.
             */

            const calculationVerified =
                jsTotal === phpTotal;


            // =====================================
            // DISPLAY ONE RESULT
            // =====================================

            document.getElementById("resultArea")
                .innerHTML = `

                <div class="employee-name">

                    <span>Employee</span>

                    <strong>
                        ${phpResult.name}
                    </strong>

                </div>


                <div class="result-row">

                    <span>
                        Annual Gross Income
                    </span>

                    <strong>
                        ${money(
                            phpResult.annualGrossIncome
                        )}
                    </strong>

                </div>


                <div class="result-row">

                    <span>
                        Less: PhilHealth Contribution
                    </span>

                    <strong>
                        ${money(
                            phpResult.philhealth
                        )}
                    </strong>

                </div>


                <div class="result-row">

                    <span>
                        Taxable Income
                    </span>

                    <strong>
                        ${money(
                            phpResult.taxableIncome
                        )}
                    </strong>

                </div>


                <div class="result-row">

                    <span>
                        BIR Income Tax
                    </span>

                    <strong>
                        ${money(
                            phpResult.incomeTax
                        )}
                    </strong>

                </div>


                <div class="result-row total">

                    <span>
                        Total Deductions
                    </span>

                    <strong>
                        ${money(
                            phpResult.totalDeductions
                        )}
                    </strong>

                </div>


                <div class="result-row net">

                    <span>
                        Estimated Net Annual Income
                    </span>

                    <strong>
                        ${money(
                            phpResult.netAnnualIncome
                        )}
                    </strong>

                </div>


                <div class="verified">

                    <span>✓</span>

                    <div>

                        <strong>
                            Calculation Complete
                        </strong>

                        <p>
                            PHP and JavaScript processed
                            the same input and produced
                            the same result.
                        </p>

                    </div>

                </div>

            `;

        })

        .catch(error => {

            button.disabled = false;

            button.innerHTML =
                "<span>🧮</span> Compute";


            console.error(error);

            alert(
                "There was a problem connecting to PHP."
            );

        });

    });



/*
|--------------------------------------------------------------------------
| MONEY FORMAT
|--------------------------------------------------------------------------
*/

function money(value) {

    return "₱" +
        Number(value).toLocaleString(
            "en-PH",
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
}