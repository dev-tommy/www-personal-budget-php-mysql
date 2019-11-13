function setActualDate() {
    var today = new Date().toISOString().substring(0, 10);
    document.getElementById('defaultToday').value = today;
    document.getElementById('defaultToday').max = today;
}

function calculateBalance() {
    var sumIncomes = document.getElementById("sum-of-incomes").innerText.replace(',', '.');
    var sumExpenses = document.getElementById("sum-of-expenses").innerText.replace(',', '.');
    var result = 'Twój bilans: <span class="h1"> ' + (sumIncomes - sumExpenses).toFixed(2).replace('.', ',') + " zł </span>";
    if (sumIncomes - sumExpenses >= 0) {
        balance.style.color = "green";
        document.getElementById("balance").innerHTML = result;
        document.getElementById("balanceComment").innerHTML = '<div><i class="material-icons" style="font-size: 50px">sentiment_very_satisfied</i></div> Gratulacje. Świetnie zarządzasz finansami!';
    }
    else if (sumIncomes - sumExpenses < 0) {
        balance.style.color = "red";
        document.getElementById("balance").innerHTML = result;
        document.getElementById("balanceComment").innerHTML = '<div><i class="material-icons" style="font-size: 50px">sentiment_dissatisfied</i></div> Uważaj, wpadasz w długi!';
    }

}


function setDate(fromInputDateId, toInputDateId) {
    document.getElementById(fromInputDateId).value = new Date().toISOString().substring(0, 10);
    document.getElementById(toInputDateId).value = new Date().toISOString().substring(0, 10);
    document.getElementById("setBalanceDates").disabled = true;
}


function enableButton() {
    document.getElementById("setBalanceDates").disabled = false;
}

function showBalance(fromInputDateId, toInputDateId) {
    location.href = "viewBalance-bs.php?startDate=" + document.getElementById(fromInputDateId).value + "&endDate=" + document.getElementById(toInputDateId).value;
}


function setPeriod(period) {
    switch (period) {
        case 'previousMonth':
            location.href = "viewBalance-bs.php?periodBalance=previousMonth";
            break;
        case 'currentYear':
            location.href = "viewBalance-bs.php?periodBalance=currentYear";
            break;
        default:
            location.href = "viewBalance-bs.php?periodBalance=currentMonth";
            break;
    }
}