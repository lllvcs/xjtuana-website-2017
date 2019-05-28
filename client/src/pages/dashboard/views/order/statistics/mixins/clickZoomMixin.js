export default {
  mounted () {
    let zoomSize = 6
    this.chart.on('click', (params) => {
      this.chart.dispatchAction({
        type: 'dataZoom',
        startValue: this.categories[Math.max(params.dataIndex - zoomSize / 2, 0)],
        endValue: this.categories[Math.min(params.dataIndex + zoomSize / 2, this.data.length - 1)],
      })
    })
  },
}
