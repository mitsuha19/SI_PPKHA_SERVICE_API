<div class="row">
    <div class="graph-container">
        <div class="pie-chart">
            <h5>Jumlah Mahasiswa Tiap Kategori</h5>
            <canvas id="myChart2"></canvas>
        </div>
        <div class="bar-chart">
            <h5>Perbandingan Pengisian Questioner</h5>
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Chart 1: Jumlah Mahasiswa Tiap Kategori
        const chartData1 = @json($jumlah_mahasiswa_tiap_kategori);

        const config1 = {
            type: 'bar',
            data: {
                labels: chartData1.labels,
                datasets: [{
                    label: 'Total Responden',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.3)',
                        'rgba(54, 162, 235, 0.3)',
                        'rgba(255, 206, 86, 0.3)',
                        'rgba(75, 192, 192, 0.3)',
                        'rgba(153, 102, 255, 0.3)',
                        'rgba(255, 159, 64, 0.3)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ],
                    data: chartData1.data,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const total = chartData1.data.reduce((sum, value) => sum + value, 0);
                                const value = tooltipItem.raw;
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${chartData1.labels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };
        new Chart(document.getElementById('myChart'), config1);

        // chart 2 perbandingan persentase pie chart total mahasiswa dengan yang mengisi survey
        const chartData2 = @json($perbandingan_pengisian_questioner);
        const config2 = {
            type: 'pie',
            data: {
                labels: chartData2.labels,
                datasets: [{
                    label: 'Total Responden',
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.3)',
                        'rgba(54, 162, 235, 0.3)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                    ],
                    data: chartData2.data,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const total = chartData2.data.reduce((sum, value) => sum + value, 0);
                                const value = tooltipItem.raw;
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${chartData2.labels[tooltipItem.dataIndex]}: ${value} (${
                                    percentage
                                }%)`;
                            },
                        },
                    },
                },
            },
        };

        new Chart(document.getElementById('myChart2'), config2);
    });
</script>
