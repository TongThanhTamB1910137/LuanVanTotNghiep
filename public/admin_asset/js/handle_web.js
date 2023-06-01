$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function preview_image(input) {
    var file = $("input[type=file]").get(0).files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function () {
            $("#preview_images").attr("src", reader.result);
        };

        reader.readAsDataURL(file);
    }
}

function preview_image_add(input) {
    var file = $("input[type=file]").get(0).files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function () {
            $("#hinh_anh_add").attr("src", reader.result);
        };

        reader.readAsDataURL(file);
    }
}


$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$().ready(function () {
    $("#notify-del-mal").delay(2000);
    $("#notify-del-mal").hide(1200);
    // DrawOrders();
    // showMaterials();
    //#region thong ke 5 san pham doc ban nhieu
    function DrawOrders() {
        $.ajax({
            type: "post",
            url: "/admin/draworders",
            dataType: "json",
            success: function (response) {
                console.log(response)
                var xValues = response.top_sale_name;
                var yValues = response.top_sale_num;
                var barColors = [
                    "#ff6384",
                    "#ff9f40",
                    "#ffcd56",
                    "#4bc0c0",
                    "#36a2eb",
                ];

                new Chart("top-product-sale", {
                    type: "pie",
                    data: {
                        labels: xValues,
                        datasets: [
                            {
                                backgroundColor: barColors,
                                data: yValues,
                            },
                        ],
                    },
                    options: {
                        title: {
                            display: true,
                            fontSize: 16,
                            fontColor: "black",
                            text: "TOP 5 SẢN PHẨM BÁN NHIỀU NHẤT",
                        },
                    },
                    
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {},
        });
    }

    //#endregion
});

$("#dropdownMenuButton").click(function (e) {
    // e.preventDefault();
    $(".dropdown-toggle").dropdown("toggle");
    var dropdownElementList = [].slice.call(
        document.querySelectorAll(".dropdown-toggle")
    );
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});

// them nguyen lieu moi
$("#btn-show-addmal").click(function (e) {
    // e.preventDefault();
    getMaterialUnit();
    showModalAdd();

    function getMaterialUnit() {
        $.ajax({
            type: "get",
            url: "/admin/them-nguyen-lieu-ajax",
            dataType: "json",
            success: function (response) {
                setSelectOtption(response);
                showModalAdd();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError);
            },
        });
    }

    function showModalAdd() {
        $("#addMaterialModal").modal("show");
    }

    function setSelectOtption(response) {
        var json_data = response.dv_ngl;
        var result = [];
        //convert json to array
        for (var i in json_data) {
            result.push([json_data[i].ten_don_vi]);
        }
        var stringSelect = "";

        for (let i = 0; i < result.length; i++) {
            stringSelect +=
                "<option value='" + result[i] + "'>" + result[i] + "</option>";
        }

        document.getElementById("selectUnit").innerHTML = stringSelect;
        var sel = document.getElementById("selectUnit");
        sel.selectedIndex = "0";
    }
});

function showMaterials() {
    $.ajax({
        type: "get",
        url: "/admin/nguyen-lieu-ajax",
        dataType: "json",
        success: function (response) {
            $("tbody").html("");
            $.each(response.nguyenlieu, function (key, item) {
                var timeIn = new Date(item.ngay_nhap * 1000);
                var timeIn_D = timeIn.getDate();
                var timeIn_M = timeIn.getMonth() + 1;
                var timeIn_Y = timeIn.getFullYear();

                var timeExpired = new Date(item.ngay_het_han * 1000);
                var timeE_D = timeExpired.getDate();
                var timeE_M = timeExpired.getMonth() + 1;
                var timeE_Y = timeExpired.getFullYear();

                $("tbody").append(
                    "<tr>\
                    <td>" +
                        item.id +
                        "</td>\
                    <td>" +
                        item.ten_nglieu +
                        "</td>\
                    <td>" +
                        item.gia_nhap +
                        "</td>\
                    <td>" +
                        item.hinh_anh +
                        "</td>\
                    <td>" +
                        item.so_luong +
                        "</td>\
                    <td>" +
                        item.don_vi_nglieu +
                        "</td>\
                    <td>" +
                        timeIn_D +
                        "-" +
                        timeIn_M +
                        "-" +
                        timeIn_Y +
                        "</td>\
                    <td>" +
                        timeE_D +
                        "-" +
                        timeE_M +
                        "-" +
                        timeE_Y +
                        "</td>\
                    <td>" +
                        item.trang_thai +
                        "</td>\
                    <td><button class='deletebtn' type='button' value='" +
                        item.id +
                        "'>del</button>\
                  <button class=editbtn' type='button' value='" +
                        item.id +
                        "'>edit</button></td>\
                     < /tr>"
                );
            });
        },
    });
}

$("#editbtn").click(function (e) {
    // e.preventDefault();
    $("#editMaterialModal").modal("show");
});

$("#btn-add-mal").click(function (e) {
    // e.preventDefault();
    sendValueToServe();

    function sendValueToServe() {
        // var data = { name: 'David', marks: 97, distinction: true };
        var nameMal = document.getElementById("inputNameMal").value;
        var numberMal = document.getElementById("soluong").value;
        var dateEpx = document.getElementById("ngay_het_han").value;
        var importPrices = document.getElementById("gia_nhap").value;
        var malUnit = $("#selectUnit option:selected").text();
        var timec = new Date(dateEpx).getTime() / 1000;
        $.ajax({
            type: "post",
            url: "/admin/them-nguyen-lieu-ajax1",
            data: {
                ten_nl: nameMal,
                so_luong: numberMal,
                gia_nhap: importPrices,
                ngay_het_han: timec,
                don_vi_nglieu: malUnit,
            },

            dataType: "json",
            success: function (response) {
                $("#addMaterialModal").modal("hide");
                $("#addMaterialModal").find("input").val(" ");
                // showMaterials();
                if (response.result_insert == "success") {
                    toastr.success("them thanh cong");
                } else {
                    toastr.error("them that bai");
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    }
});

//#region delete material
//set id de xoa
$(document).on("click", ".deletebtn", function () {
    var stud_id = $(this).val();
    $("#DeleteModal").modal("show");
    $("#deleteing_id").val(stud_id);
});

$(document).on("click", ".delete_student", function (e) {
    // e.preventDefault();
    var id = $("#deleteing_id").val();
    $.ajax({
        type: "post",
        url: "/admin/xoa-nguyen-lieu-ajax/" + id,
        dataType: "json",
        success: function (response) {
            $("#DeleteModal").modal("hide");
            // showMaterials();
            toastr.success("xoa thanh cong");
        },
    });
});
//#endregion

/*show visitor view */
//set tỉme show visitor
$(document).ready(function () {
    setInterval(showVisitor, 10000);
    function showVisitor() {
        $.ajax({
            type: "post",
            url: "/admin/getvisitor",
            dataType: "json",
            success: function (response) {
                if(response.count_vistor) {
                    document.getElementById("visitor_views").innerHTML=response.count_vistor;
                }
            },
        });
    }
});

//set date default
$(document).ready(function () {
    let now = new Date();

    let day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    let year = now.getFullYear();
    var today = year + "-" + month + "-" + day;
    $("#dateSort").val(today);
    $("#datethongke").val(today);
    $("#dataExport").val(today);
});

//#region  filter manager material use by date
function sortByDay() {
    var getDaySort = document.getElementById("dateSort").value;
    $.ajax({
        type: "post",
        url: "/admin/sort-Mal-By-Day",
        data: {
            daySort: getDaySort,
        },
        dataType: "json",
        success: function (response) {
            $("#show-manager-material-use").html("");
            document.getElementById("show-manager-material-use").html = "";
            if (response.flagerror == 0) {
                $("#show-manager-material-use").append(
                    $.each(response.dataget, function (key, item) {
                        $("#show-manager-material-use").append(
                            "<tr>\
                        <td>" +
                                item.id +
                                "</td>\
                        <td>" +
                                item.id_nguyen_lieu +
                                "</td>\
                        <td>" +
                                item.so_luong +
                                "</td>\
                        <td>" +
                                item.don_gia +
                                "</td>\
                        <td>" +
                                item.ngay_tong_ket +
                                "</td>\
                        <td>" +
                                item.trang_thai +
                                "</td>\
                        <td>" +
                                item.don_gia * item.so_luong +
                                "</td>\
                        <td><button style='background-color:red' href='{{route(delMMU," +
                                item.id +
                                ")}}' id='delMMU' onclick='delManagerMalUse(" +
                                item.id +
                                ")' value='" +
                                item.id +
                                "'>del</button>\
                      <button value='" +
                                item.id +
                                "'>edit</button></td>\
                         < /tr>"
                        );
                    })
                );
            } else {
                $("#show-manager-material-use").append(
                    "<h3>Khong tim thay du lieu</h3>"
                );
            }
        },
    });
}

//#endregion
function delManagerMalUse(id) {
    $.ajax({
        type: "post",
        url: "url",
        data: "data",
        dataType: "dataType",
        success: function (response) {},
    });
}

//#region dashboard

// $("#statics").click(function (e) {
//     e.preventDefault();
//     let chooseType = document.getElementById("chooseTypeStatis").value;
//     let showTur = document.getElementById("showtiendoanhthu");
//     let showDateTur = document.getElementById("showdatedoanhthu");
//     document.getElementById("showdoanhso").innerHTML = " ";
//     if (chooseType == 1) {
//         var getDate = document.getElementById("datethongke").value;
//         //statis by date
//         $.ajax({
//             type: "post",
//             url: "/admin/statisbydate",
//             data: {
//                 statisDate: getDate,
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.result_error == "0") {
//                     let showTur =
//                         "Doanh thu ngay " +
//                         getDate +
//                         " la: " +
//                         response.result +
//                         " VND";
//                     // document.getElementById("showdoanhthu").hidden = false;
//                     document.getElementById("showdoanhso").append(showTur);
//                 } else if (response.result_error == "1") {
//                     document
//                         .getElementById("showdoanhso")
//                         .append("khong the thong ke");
//                 }
//             },
//         });
//     } else {
//         //statis by month

//         var getMonthS = document.getElementById("monthchoose").value;

//         $.ajax({
//             type: "post",
//             url: "/admin/statisbymonth",
//             data: {
//                 getMonth: getMonthS,
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.result_error == "0") {
//                     let showTur =
//                         "Doanh thu thang " +
//                         getMonthS +
//                         " la: " +
//                         response.month_turnover +
//                         " VND";
//                     document.getElementById("showdoanhso").append(showTur);
//                 } else if (response.result_error == "1") {
//                     document
//                         .getElementById("showdoanhso")
//                         .append("khong the thong ke");
//                 }
//             },
//         });
//     }
// });

// $(document).ready(function () {
//     document.getElementById("monthpicker").hidden = true;
//     // document.getElementById("showdoanhthu").hidden = true;
// });

//event change type statis
$("#chooseTypeStatis").change(function (e) {
    // e.preventDefault();
    var typeStatis = document.getElementById("chooseTypeStatis").value;
    var monthPicker = document.getElementsByClassName("month-choose");
    //date 1 month 2
    if (typeStatis == 1) {
        document.getElementById("monthpicker").hidden = true;
        document.getElementById("datepicker").hidden = false;
    } else {
        document.getElementById("monthpicker").hidden = false;
        document.getElementById("datepicker").hidden = true;
    }
});

// draw chart static 12 month
$(document).ready(function () {
    $.ajax({
        type: "post",
        url: "/admin/drawstatisyear",
        dataType: "json",
        success: function (res) {
            let data = res.data;
            drawstatisyear(data);
        },
    });

    function drawstatisyear(dateStatis) {
        var nowYear = new Date();
        var getNowYear = nowYear.getFullYear();
        var xValues = [
            "THÁNG 1",
            "THÁNG 2",
            "THÁNG 3",
            "THÁNG 4",
            "THÁNG 5",
            "THÁNG 6",
            "THÁNG 7",
            "THÁNG 8",
            "THÁNG 9",
            "THÁNG 10",
            "THÁNG 11",
            "THÁNG 12",
        ];
        var yValues = dateStatis;
        var barColors = [
            "red",
            "green",
            "blue",
            "orange",
            "brown",
            "red",
            "green",
            "blue",
            "orange",
            "brown",
            "red",
            "red",
        ];

        new Chart("show-statis-by-year", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [
                    {
                        backgroundColor: barColors,
                        data: yValues,
                        fontSize: 14,
                    },
                ],
            },
            options: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: "DOANH THU NĂM: " + getNowYear,
                    fontSize: 16,
                    fontColor: "red",
                },
                xValues: {
                    fontColor: "red",
                },
                scales: {
                    xAxes: [
                        {
                            barPercentage: 0.5,
                        },
                    ],
                },
            },
        });
    }
});

// $(document).ready(function () {
//     $.ajax({
//         type: "post",
//         url: "/admin/showSaleDaily",
//         dataType: "json",
//         success: function (res) {
//             let moneyDaily = res.today;
//             document.getElementById("numberMoney").innerHTML =
//                 moneyDaily.toLocaleString();
//         },
//     });
// });

//set default day export
$(document).ready(function () {
    document.getElementById("chooseDayExport").hide = false;
    $("#exportbyMonth").hide();
    $("#chooseTypeExport").change(function (e) {
        e.preventDefault();
        var getSelect = document.getElementById("chooseTypeExport").value;
        if (getSelect == 1) {
            $("#exportbyMonth").hide();
            $("#chooseDayExport").show();
        } else {
            $("#exportbyMonth").show();
            $("#chooseDayExport").hide();
        }
    });
});

$("#exportFile").click(function (e) {
    e.preventDefault();
    $("#exportModel").modal("show");
});

$(document).ready(function () {
    $nowMonth = new Date().getMonth() + 1;
    var dat = document.getElementById("chooseMonthExport").value;
    var element = document.getElementById("chooseMonthExport");
    element.value = $nowMonth;
});

// $('.show-more-statis').hide();
// //hover view more
// var viewmore = document.getElementsByClassName("sale-by-date");
// $(viewmore).hover(function () {
//         // over
//         $('.show-more-statis').show();
//     }, function () {
//         // out
//         $('.show-more-statis').hide();
//     }
// );

//#endregion
$(document).ready(function () {
    $("#alert-success").delay(2000);
    $("#alert-successl").hide(1200);
});


// $("#addModalMMU").click(function (e) {
//     e.preventDefault();
//     $("#addmmu").modal("show");
//     // console.log('adasd');
// });
// $("#addmmuhandle").click(function (e) {
//     e.preventDefault();
//     $("#addmmu").modal("hide");
//     // console.log('adasd');
// });

var checkboxes = document.getElementById("sizeChoose");

var getValueCheck = document.getElementById("sizeChoose");
// if (getValueCheck == "Nhỏ") {

// }

//active menu when click
$(document).ready(function () {
    //get url current
    var getUrlCurrent = window.location.href;
    //split string to arr
    var ar = getUrlCurrent.split("/");

    var getLength = ar.length;
    var strURl = ar[getLength - 1];

    var urlp = strURl.split("?");
    var getLength = ar.length;
    // //get parameters last
    var strURl = ar[getLength - 1];
    //get parameters first
    var urlPage = urlp[0];

    // //get list menu
    var ul = document.getElementsByTagName("ul");
    var li = ul[0].getElementsByTagName("li");

    for (var i = 0; i < li.length; i++) {
        var getStr = li[i].innerText;
        var str = toSlug(getStr);
        if (str == strURl || str == urlPage) {
            $("li:eq(" + i + ")").addClass("active");
        }
    }
});

//convert string to slug
function toSlug(str) {
    // Chuyển hết sang chữ thường
    str = str.toLowerCase();

    // xóa dấu
    str = str
        .normalize("NFD") // chuyển chuỗi sang unicode tổ hợp
        .replace(/[\u0300-\u036f]/g, ""); // xóa các ký tự dấu sau khi tách tổ hợp

    // Thay ký tự đĐ
    str = str.replace(/[đĐ]/g, "d");

    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, "");

    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, "-");

    // Xóa ký tự - liên tiếp
    str = str.replace(/-+/g, "-");

    // xóa phần dư - ở đầu & cuối
    str = str.replace(/^-+|-+$/g, "");

    // return
    return str;
}

//show more dropdown info account
$(document).ready(function () {
    const dropdownMenuButton1 = document.querySelector("#dropdownMenuButton1");
    dropdownMenuButton1.onclick = () => {
        document.querySelector(".dropdown-menu").classList.toggle("show");
    };
});

//show modal export file exel
$("#exportExel").click(function (e) {
    e.preventDefault();
    // setInterval(hideExport, 3000);
    function hideExport() {
        $("#exportModel").modal("hide");
    }
});

// *** (month and year only) ***
$(document).ready(function () {
    $("#datepickyear").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true, //to close picker once year is selected
    });

    //set year defaul
    var yearcurr = new Date().getFullYear();
    document.getElementById("datepickyear").val = yearcurr;
});

//search products live use ajax
$("#keysearch_product").on("keyup", function () {
    $value = $(this).val();
    $.ajax({
        type: "get",
        url: "/admin/tim-kiem-san-pham",
        data: {
            search: $value,
        },
        success: function (data) {
            $("#indexpro").html("");
            $("#indexpro").html(data.html);
        },
    });
});

//update status product (show or hide)
$(".btnstatus").click(function (e) {
    e.preventDefault();
    var idchange = $(this).val();
    $.ajax({
        type: "post",
        url: "/admin/cap-nhat-trang-thai",
        data: {
            id: idchange,
        },
        dataType: "json",
        success: function (response) {
            location.reload();
            toastr.success("Thay đổi trạng thái thành công");
        },
    });
});

//set money buy products daily
$(document).ready(function () {
    $.ajax({
        type: "get",
        url: "/admin/get-sales",
        dataType: "json",
        success: function (response) {
            if(response.data) {
                document.getElementById("numberMoney").innerHTML = response.data;
            }
        },
    });
});

//test image
$(document).ready(function () {
    // var image='<img src="'+"{{asset('uploads/product/1657402889.png')}}"+'">';
    document.getElementById("test-image").innerHTML = image;
});


