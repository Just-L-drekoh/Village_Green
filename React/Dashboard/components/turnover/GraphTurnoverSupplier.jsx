import React from 'react';
import { Bar } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

const GraphTurnoverSupplier = ({ data }) => {
    if (!data.length) {
        return <p>Aucune donnée disponible.</p>;
    }

    const labels = data.map(item => item.reference); 
    const turnoverValues = data.map(item => parseFloat(item.turnover));
    const lastName = data.map(item => item.lastName)
    const chartData = {
        labels: labels,
        datasets: [
            {
                label: "Chiffre d'affaires (€)",
                data: turnoverValues,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    };

    const options = {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Graphique du chiffre d'affaires par fournisseur ${lastName} `
            },
            tooltip: {
                callbacks: {
                    label: (tooltipItem) => {
                        return `${tooltipItem.raw.toFixed(2)} €`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    return (
        <>
            <h1>Graphique du Chiffre d'Affaires</h1>
            <Bar data={chartData} options={options} />
        </>
    );
};

export default GraphTurnoverSupplier;
