import React from 'react'
import { Line } from 'react-chartjs-2'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

const GraphTurnover = ({ year, data }) => {
    const chartData = {
        labels: data.map(item => item.month), 
        datasets: [
            {
                label: 'Chiffre d\'affaires (€)',
                data: data.map(item => parseFloat(item.chiffreAffaire)), // Extract chiffreAffaire values
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }
        ]
    }

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
    }

    return (
        <>
            <h1>Graph Turnover</h1>
            <p>Année: {year}</p>
            <p>Nombre de mois de données: {data.length}</p>

            <Line data={chartData} options={options} />

            <ul>
                {data.map((item, index) => (
                    <li key={index}>
                        <strong>{item.month}</strong>: 
                        <span>{parseFloat(item.chiffreAffaire).toFixed(2)} €</span>
                    </li>
                ))}
            </ul>
        </>
    );
}

export default GraphTurnover;
