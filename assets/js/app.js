document.addEventListener('DOMContentLoaded', () => {
    loadTransactions();

    // Add Transaction Form Handler
    document.getElementById('add-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            date: document.getElementById('t-date').value,
            description: document.getElementById('t-desc').value,
            type: document.getElementById('t-type').value,
            amount: document.getElementById('t-amount').value
        };

        try {
            const response = await fetch('api.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                // Reset form
                e.target.reset();
                // Set default date again if needed
                document.getElementById('t-date').valueAsDate = new Date();

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTransactionModal'));
                modal.hide();

                // Reload data
                loadTransactions();
                showToast('Transaction added successfully!');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to add transaction.');
        }
    });

    // Set default date to today
    document.getElementById('t-date').valueAsDate = new Date();
});

async function loadTransactions() {
    try {
        const response = await fetch('api.php?action=list');
        const result = await response.json();

        if (result.status === 'success') {
            renderTable(result.data);
            calculateStats(result.data);
        }
    } catch (error) {
        console.error('Error loading transactions:', error);
        document.getElementById('transaction-list').innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load data.</td></tr>';
    }
}

function renderTable(transactions) {
    const tbody = document.getElementById('transaction-list');
    tbody.innerHTML = '';

    if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No transactions found. Start by adding one!</td></tr>';
        return;
    }

    transactions.forEach(t => {
        const tr = document.createElement('tr');
        tr.className = 'fade-in';

        const isIncome = t.type === 'income';
        const badgeClass = isIncome ? 'badge-income' : 'badge-expense';
        const amountClass = isIncome ? 'text-success' : 'text-danger';
        const sign = isIncome ? '+' : '-';

        tr.innerHTML = `
            <td class="ps-4">${formatDate(t.date)}</td>
            <td>${escapeHtml(t.description)}</td>
            <td><span class="${badgeClass}">${capitalize(t.type)}</span></td>
            <td class="text-end fw-bold ${amountClass}">${sign}$${parseFloat(t.amount).toFixed(2)}</td>
            <td class="text-end pe-4">
                <button class="btn btn-sm btn-outline-danger border-0" onclick="deleteTransaction('${t.id}')" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function calculateStats(transactions) {
    let income = 0;
    let expense = 0;

    transactions.forEach(t => {
        const amount = parseFloat(t.amount);
        if (t.type === 'income') {
            income += amount;
        } else {
            expense += amount;
        }
    });

    const balance = income - expense;

    animateValue('total-income', income);
    animateValue('total-expense', expense);
    animateValue('current-balance', balance, true);
}

function animateValue(id, value, isBalance = false) {
    const el = document.getElementById(id);
    // Simple direct update for now, can add animation later
    const formatted = '$' + Math.abs(value).toFixed(2);
    el.textContent = (isBalance && value < 0 ? '-' : '') + formatted;
    if (isBalance) {
        el.className = value >= 0 ? 'fw-bold' : 'fw-bold text-warning'; // Warning color if negative
    }
}

async function deleteTransaction(id) {
    if (!confirm('Are you sure you want to delete this transaction?')) return;

    try {
        const response = await fetch('api.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });

        const result = await response.json();

        if (result.status === 'success') {
            loadTransactions();
            showToast('Transaction deleted.');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete transaction.');
    }
}

function showToast(message) {
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('toast-message');
    toastBody.textContent = message;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
