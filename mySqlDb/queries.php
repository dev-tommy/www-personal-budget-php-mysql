<?php
function getIncomeQuery($id, $startDate, $endDate)
{
    return  "
        SELECT
            i_userid.name AS 'Category',
            SUM(i.amount) AS 'Sum of amounts'
        FROM
            incomes AS i,
            incomes_category_assigned_to_userid_$id AS i_userid
        WHERE
            i_userid.id = i.income_category_assigned_to_user_id AND
            i.date_of_income >= '$startDate' AND
            i.date_of_income <= '$endDate' AND i.user_id='$id' 
            GROUP BY i.income_category_assigned_to_user_id 
    ";

}

function getExpenseQuery($id, $startDate, $endDate)
{
    return  "
        SELECT
            e_userid.name AS 'Category',
            SUM(e.amount) AS 'Sum of amounts'
        FROM
            expenses AS e,
            expenses_category_assigned_to_userid_$id AS e_userid
        WHERE
            e_userid.id = e.expense_category_assigned_to_user_id AND
            e.date_of_expense >= '$startDate' AND
            e.date_of_expense <= '$endDate' AND
            e.user_id = '$id'
        GROUP BY e.expense_category_assigned_to_user_id
    ";
}

