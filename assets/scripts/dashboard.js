$.post('./api/call_count_all').done((data)=>{
    var c__ = $.map(data.data, function(value, index) {
        $('#'+index).text(value);
    });
}).fail((e)=>{

});

$.post('./api/call_tren',{tahun:2018}).done((data)=>{

    var ctx = document.getElementById('area-tren_nilai').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labelBulan,
            datasets: [{
                
                data: data.dataSheet,
                backgroundColor: 'rgba(1, 1, 1, 0.5)',
                fillColor: "#79D1CF",
                strokeColor: "#79D1CF",
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        },
    });
}).fail((e)=>{

});

$.get('./api/call_budget').done((data)=>{
    var ctx = document.getElementById('pie-budget').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.label,
            datasets: [{
                
                data: data.data,
                backgroundColor: data.color,
                fillColor: "#79D1CA",
                strokeColor: "#79D1CF",
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            maintainAspectRatio:false,
            responsive: true, 
        },
    });
}).fail((e)=>{

});


$.get('./api/call_top_seller').done((data)=>{
    var ctx = document.getElementById('line-seller').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: data.label,
            datasets: [{
                
                data: data.data,
                backgroundColor: data.color,
                fillColor: "#79D1CF",
                strokeColor: "#79D1CF",
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            maintainAspectRatio:false,
            responsive: true, 
        },
    });
}).fail((e)=>{

});

function getDataProv(id=null){
    $.post('./api/call_jumlah',{id:id}).done((data)=>{
        $('.leaflet-popup-content').html(`
            <h3>`+data.data.provinsi+`<h3>
            <h5 style="color:red;">Jumlah : `+data.data.jumlah+`</h5>
        `);
    }).fail((e)=>{

    });
}


$.post('./api/call_json').done((data)=>{

    var GEOJSON = data;

    var mapOptions = {
      center: [-1.3770541,118.7451594],
      zoom: 5
    } 
    var peta = new L.map('peta', mapOptions);

    L.geoJSON(GEOJSON,{
       onEachFeature: function (f, l) {
            // getDataProv(f.properties.id_1);
            l.bindPopup(JSON.stringify(f.properties,null,' ').replace(/[\{\}"]/g,''));
       }
    }).addTo(peta).on({
        click: function(e) {
            
            isClicked = true
            // getDataProv();

            // $('.leaflet-popup-content').html(e.sourceTarget.feature.properties.id_1); 
            $('.leaflet-popup-content').html(`
                <div class="overlay">
                    <i class="fas fa-2x fa-cog fa-spin"></i>
                </div>
            `);

            getDataProv(e.sourceTarget.feature.properties.id_1);
        }
});

    L.tileLayer(
        'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', 
        {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
        }
    ).addTo(peta);

}).fail(()=>{

});