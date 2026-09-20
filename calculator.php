<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["employee_name"] ?? "");

    $monthlySalary =
        floatval($_POST["monthly_salary"] ?? 0);

    $otherIncome =
        floatval($_POST["other_income"] ?? 0);

    $months =
        intval($_POST["months_worked"] ?? 12);



    if (
        $name === "" ||
        $monthlySalary <= 0 ||
        $months < 1 ||
        $months > 12
    ) {

        $response = [
            "success" => false,
            "message" => "Please enter valid employee information."
        ];

        header("Content-Type: application/json");

        echo json_encode($response);

        exit;
    }


    $annualGrossIncome =
        ($monthlySalary * $months)
        + $otherIncome;

    $philhealth =
        $annualGrossIncome * 0.025;


    $taxableIncome =
        $annualGrossIncome
        - $philhealth;



    if ($taxableIncome <= 250000) {

        $incomeTax = 0;

    } elseif ($taxableIncome <= 400000) {

        $incomeTax =
            ($taxableIncome - 250000)
            * 0.15;

    } elseif ($taxableIncome <= 800000) {

        $incomeTax =
            22500
            + (($taxableIncome - 400000) * 0.20);

    } elseif ($taxableIncome <= 2000000) {

        $incomeTax =
            102500
            + (($taxableIncome - 800000) * 0.25);

    } elseif ($taxableIncome <= 8000000) {

        $incomeTax =
            402500
            + (($taxableIncome - 2000000) * 0.30);

    } else {

        $incomeTax =
            2202500
            + (($taxableIncome - 8000000) * 0.35);
    }


    $totalDeductions =
        $philhealth + $incomeTax;

    $netAnnualIncome =
        $annualGrossIncome - $totalDeductions;


    $response = [

        "success" => true,

        "name" => $name,

        "annualGrossIncome" =>
            round($annualGrossIncome, 2),

        "philhealth" =>
            round($philhealth, 2),

        "taxableIncome" =>
            round($taxableIncome, 2),

        "incomeTax" =>
            round($incomeTax, 2),

        "totalDeductions" =>
            round($totalDeductions, 2),

        "netAnnualIncome" =>
            round($netAnnualIncome, 2)

    ];


    header("Content-Type: application/json");

    echo json_encode($response);

    exit;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Calculator - Government Employee Tax Calculator</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>


<header class="header">

    <div class="brand">

        <div class="brand-logo">
            P
        </div>

        <div>

            <h1>PhilHealth</h1>

            <p>Your Partner in Health</p>

        </div>

    </div>


    <div class="header-title">

        <h2>
            Government Employee Tax Calculator
        </h2>

        <p>
            BIR Income Tax Return • PhilHealth Contribution
        </p>

    </div>


    <div class="header-right">

        <strong>
            Government Employees
        </strong>

        <span>
            Tax & Contribution System
        </span>

    </div>

</header>


<nav class="navbar">

    <a href="index.php">
        Home
    </a>

    <a href="calculator.php" class="active">
        Calculator
    </a>

    <a href="contact.php">
        Contact Us
    </a>

</nav>


<main class="calculator-page">


    <div class="calculator-heading">

        <h1>
            BIR & PhilHealth Calculator
        </h1>

        <p>
            Enter the employee information below
            and click Compute.
        </p>

    </div>


    <div class="calculator-layout">


        <!-- INPUT -->

        <section class="calculator-card">

            <div class="calculator-title">

                <div class="calculator-icon">
                    ₱
                </div>

                <div>

                    <h2>
                        Employee Information
                    </h2>

                    <p>
                        Enter employee details
                    </p>

                </div>

            </div>


            <form id="taxForm">


                <div class="form-group">

                    <label>
                        Employee Name
                    </label>

                    <input
                        type="text"
                        id="employee_name"
                        name="employee_name"
                        placeholder="Juan Dela Cruz"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Monthly Basic Salary (₱)
                    </label>

                    <input
                        type="number"
                        id="monthly_salary"
                        name="monthly_salary"
                        placeholder="30000"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Other Taxable Income (₱)
                    </label>

                    <input
                        type="number"
                        id="other_income"
                        name="other_income"
                        placeholder="5000"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Number of Months Worked
                    </label>

                    <input
                        type="number"
                        id="months_worked"
                        name="months_worked"
                        min="1"
                        max="12"
                        value="12"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="compute-button"
                    id="computeButton"
                >

                    <span>
                        🧮
                    </span>

                    Compute

                </button>


            </form>

        </section>


        <section class="result-card">


            <div class="calculator-title">

                <div class="result-icon">
                    ✓
                </div>

                <div>

                    <h2>
                        Calculation Result
                    </h2>

                    <p>
                        Government Employee Tax Summary
                    </p>

                </div>

            </div>


            <div id="resultArea">

                <div class="empty-result">

                    <div class="empty-icon">
                        ₱
                    </div>

                    <h3>
                        No Calculation Yet
                    </h3>

                    <p>
                        Enter employee information and
                        click Compute to calculate the result.
                    </p>

                </div>

            </div>


        </section>

    </div>



    <div class="disclaimer">

        <strong>Note:</strong>

        This calculator is an educational demonstration.
        It is not an official BIR or PhilHealth filing
        or payroll system.

    </div>


</main>


<footer>

    <p>
        © 2026 Government Employee Tax Calculator
    </p>

</footer>


<script src="script.js"></script>

</body>

</html>