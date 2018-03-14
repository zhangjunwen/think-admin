$(function () {
    $('#changeMap').click(function () {
        $('.bgmap').show()
        $('.map').slideDown();
    })
    $('.bgmap').click(function () {
        $('.bgmap').hide();
        $('.map').slideUp();
    })
})
var map = new AMap.Map("container", {
    resizeEnable: true,
    center: [116.397495, 39.908742],
    zoom: 15
});
var marker = new AMap.Marker({
    position: [116.397495, 39.908742],
    draggable: true,
    cursor: 'move',
});
marker.setMap(map);
var auto = new AMap.Autocomplete({
    input: "tipinput"
});
//关键词检索
AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
function select(e) {
    if (e.poi && e.poi.location) {
        map.setZoom(15);
        map.setCenter(e.poi.location);
        addMarker(e.poi.location.lng,e.poi.location.lat)
    }
}
//创建点标记
function addMarker(lng,lat) {
    getAddress(lng,lat)
    marker.setMap(null);
    marker = new AMap.Marker({
        position: [lng,lat],
        draggable: true,
        cursor: 'move',
    });
    marker.setMap(map);
    AMap.event.addListener(marker,'dragend',function (e) {
        var lng = e.lnglat.lng
        var lat = e.lnglat.lat
        getAddress(lng,lat)
    })
}
AMap.event.addListener(marker,'dragend',function (e) {
    var lng = e.lnglat.lng
    var lat = e.lnglat.lat
    getAddress(lng,lat)
})
//获取地址
function getAddress(lng,lat){
    var lnglat = [lng,lat]
    var geocoder = new AMap.Geocoder({
        radius: 1000,
        extensions: "all"
    });
    geocoder.getAddress(lnglat, function(status, result) {
        var address = result.regeocode.formattedAddress;
        document.getElementById("province").value = result.regeocode.addressComponent.province;
        document.getElementById("city").value = result.regeocode.addressComponent.city;
        document.getElementById("district").value = result.regeocode.addressComponent.district;
        document.getElementById("tipinput").value = address;
        document.getElementById("changeMap").value = address;
    });
    $('input[name="lng"]').val(lng).show();
    $('input[name="lat"]').val(lat).show();
    $('#province').show();
    $('#city').show();
    $('#district').show();
}
//鼠标点选
var clickEventListener = map.on('click', function(e) {
    var lng = e.lnglat.lng
    var lat = e.lnglat.lat
    addMarker(lng,lat);
});
