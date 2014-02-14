function chartCtrl($scope){
    $scope.chartOptions = {
        dataSource: [
            {company: 'ExonMobile', 2004: 277.02, 2005: 362.53, 2006: 398.91 },
            {company: 'General ELectric', 2004: 328.02, 2005: 348.45, 2006: 364.41 },
            {company: 'Microsoft', 2004: 297.02, 2005: 279.02, 2006: 272.68 },
            {company: 'CitiGroup', 2004: 255.3, 2005: 230.93, 2006: 246.37 },
            {company: 'P&G', 2004: 173.54, 2005: 203.52, 2006: 216.37 },
            {company: 'ROyal Dutch Shell', 2004: 131.09, 2005: 197.12, 2006: 200.32 }
        ],
        series: [
            {valueField: '2004', name: '2004', type: 'area'},
            {valueField: '2005', name: '2005', type: 'area'},
            {valueField: '2006', name: '2006', type: 'spline', color: 'mediumvioletred'}
        ],
        commonSeriesSettings: {
            argumentField: 'company',
            area: { color: 'cornflowerblue' }
        },
        legend: {
            verticalAlignment: 'bottom',
            horizontalAlignment: 'center'
        },
        title: 'Corporations market value'
    }
}
