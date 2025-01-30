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

const monthNames = [
    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
];

const GraphTurnover = ({ year, data }) => {
    const chartData = {
        labels: data.map(item => monthNames[item.month - 1] || "Inconnu"), 
        datasets: [
            {
                label: 'Chiffre d\'affaires (€)',
                data: data.map(item => parseFloat(item.chiffreAffaire)), 
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
                text: `Graphique du chiffre d'affaires pour ${year}`
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
            <p>Année: {year}</p>
            <p>Nombre de mois de données: {data.length}</p>

            <Bar data={chartData} options={options} />

            <ul>
                {data.map((item, index) => (
                    <li key={index}>
                        <strong>{monthNames[item.month - 1] || "Inconnu"}</strong>: 
                        <span> {parseFloat(item.chiffreAffaire).toFixed(2)} €</span>
                    </li>
                ))}
            </ul>
        </>
    );
}

export default GraphTurnover;
