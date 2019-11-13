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

