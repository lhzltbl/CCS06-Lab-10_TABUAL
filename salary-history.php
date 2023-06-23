<?php

require "config.php";

use App\Employee;

// Retrieve the employee number from the query string
if (isset($_GET['emp_no'])) {
    $emp_no = $_GET['emp_no'];
} else {
    die("Employee not specified.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Salary History</title>
</head>
<body>
<?php
    // Retrieve the employee information
    $sql = "SELECT CONCAT(first_name, ' ', last_name) AS employee_name, birth_date, gender, hire_date
            FROM employees
            WHERE emp_no = :emp_no
            LIMIT 1";
    $statement = $conn->prepare($sql);
    $statement->bindValue(':emp_no', $emp_no);
    $statement->execute();
    $employee = $statement->fetch();

    if (!$employee) {
        die("Employee not found.");
    }

    // Display the employee's basic information
    echo "<h1>Salary History for {$employee['employee_name']}</h1>";
    echo "<p>Birthday: {$employee['birth_date']}</p>";
    echo "<p>Gender: {$employee['gender']}</p>";
    echo "<p>Hire Date: {$employee['hire_date']}</p>";

    // Retrieve the employee's salary history
    $sql = "SELECT from_date, to_date, salary
            FROM salaries
            WHERE emp_no = :emp_no
            ORDER BY to_date DESC";
    $statement = $conn->prepare($sql);
    $statement->bindValue(':emp_no', $emp_no);
    $statement->execute();
    $salary_history = $statement->fetchAll();

    // Display the salary history in a table
    echo "<table>";
    echo "<tr><th>From Date</th><th>To Date</th><th>Salary</th></tr>";

    foreach ($salary_history as $salary) {
        echo "<tr>";
        echo "<td>{$salary['from_date']}</td>";
        echo "<td>{$salary['to_date']}</td>";
        echo "<td>\${$salary['salary']}</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo '<a href="/employees.php?dept_no=d005">Back to Employees</a>';
?>

</body>
</html>