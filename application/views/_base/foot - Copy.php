<?php
	foreach ($scripts['foot'] as $file)
	{
		$url = starts_with($file, 'http') ? $file : base_url($file);
		echo "<script src='$url'></script>".PHP_EOL;
	}
?>

<?php // Google Analytics ?>
<?php $this->load->view('_partials/ga') ?>
<script type="text/javascript">
    var $k = jQuery.noConflict();
    $k(function() {
        $k('#chooseall').click(function() {
            //alert($k('#chooseall').is(':checked'));
            $k(".chkColumns").each(function() {
                $k(this).attr('checked', $k('#chooseall').is(':checked'));
                //$k(this).prop('checked',true);
            });
            if ($k('#chooseall').is(':checked')) {
                //alert('hi');
                $k(".filterItem").show();
            } else {
                $k(".filterItem").hide();
            }
        });

        $k(".chkColumns").each(function() {
            $k(this).click(function() {
                var chkID = $k(this).attr("id");
                if ($k(this).is(':checked')) {
                    $k("#" + chkID + "Box").show();
                } else {
                    $k("#" + chkID + "Box").hide();
                }
            });
        });

        $k(".filterItem").hide();

        $k('.cal').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            yearRange: "-60:+0"
        });
        $k("#selGrade").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selGrade").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnGrade").val((array_of_checked_values));
            } else {
                $k("#hdnGrade").val('');
            }
        });
        $k("#selLevel").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selLevel").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnLevel").val((array_of_checked_values));
            } else {
                $k("#hdnLevel").val('');
            }
            getGrade(array_of_checked_values);
        });
        $k("#selDepartment").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selDepartment").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnDept").val((array_of_checked_values));
            } else {
                $k("#hdnDept").val('');
            }
            getDesignation(array_of_checked_values);
        });
        $k("#selDesignation").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selDesignation").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnDesg").val((array_of_checked_values));
            } else {
                $k("#hdnDesg").val('');
            }
        });
        $k("#selSpecializationGrad").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selSpecializationGrad").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnSpecializationGrad").val((array_of_checked_values));
            } else {
                $k("#hdnSpecializationGrad").val('');
            }
        });
        $k("#selSpecializationProf").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selSpecializationProf").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnSpecializationProf").val((array_of_checked_values));
            } else {
                $k("#hdnSpecializationProf").val('');
            }
        });
        $k("#selLocation").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selLocation").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnLoc").val((array_of_checked_values));
            } else {
                $k("#hdnLoc").val('');
            }
        });
        $k("#selReporting").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selReporting").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnReporting").val((array_of_checked_values));
            } else {
                $k("#hdnReporting").val('');
            }
        });
        $k("#selReviewing").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selReviewing").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnReviewing").val((array_of_checked_values));
            } else {
                $k("#hdnReviewing").val('');
            }
        });
        $k("#selHOD").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selHOD").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnHOD").val((array_of_checked_values));
            } else {
                $k("#hdnHOD").val('');
            }
        });
        $k("#selState").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selState").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnState").val((array_of_checked_values));
            } else {
                $k("#hdnState").val('');
            }
        });
        $k("#selLocationHQ").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selLocationHQ").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnLocHQ").val((array_of_checked_values));
            } else {
                $k("#hdnLocHQ").val('');
            }
        });
        $k("#selBank").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selBank").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnBank").val((array_of_checked_values));
            } else {
                $k("#hdnBank").val('');
            }
        });
        $k("#selGraduation").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selGraduation").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnGraduation").val((array_of_checked_values));
            } else {
                $k("#hdnGraduation").val('');
            }
            getSpecializationGrad(array_of_checked_values);
        });
        $k("#selHQ").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selHQ").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnHQ").val((array_of_checked_values));
            } else {
                $k("#hdnHQ").val('');
            }
            getSpecialization(array_of_checked_values);
        });
        $k("#selgBorU").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selgBorU").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdngBorU").val((array_of_checked_values));
            } else {
                $k("#hdngBorU").val('');
            }
        });
        $k("#selProfessional").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selProfessional").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnProfessional").val((array_of_checked_values));
            } else {
                $k("#hdnProfessional").val('');
            }
            getSpecializationProf(array_of_checked_values);
        });
        $k("#selpBorU").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selpBorU").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnpBorU").val((array_of_checked_values));
            } else {
                $k("#hdnpBorU").val('');
            }
        });
        $k("#selAnniversaryDate").multiselect({
            selectedList: 5,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selAnniversaryDate").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnAnniversaryDate").val((array_of_checked_values));
            } else {
                $k("#hdnAnniversaryDate").val('');
            }
        });
        $k("#selBGroup").multiselect({
            selectedList: 5,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selBGroup").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnBGroup").val((array_of_checked_values));
            } else {
                $k("#hdnBGroup").val('');
            }
        });
        $k("#selHire").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selHire").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnHire").val((array_of_checked_values));
            } else {
                $k("#hdnHire").val('');
            }
        });
        $k("#selReaSep").multiselect({
            selectedList: 1,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selReaSep").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnReaSep").val((array_of_checked_values));
            } else {
                $k("#hdnReaSep").val('');
            }
        });
        $k("#selEmpType").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selEmpType").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnEmpType").val((array_of_checked_values));
            } else {
                $k("#hdnEmpType").val('');
            }
        });
        $k("#selEmpStatusType").multiselect({
            selectedList: 2,
            height: '100',
            minWidth: 'auto',
            noneSelectedText: ''
        }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            var array_of_checked_values = $k("#selEmpStatusType").multiselect("getChecked").map(function() {
                return this.value;
            }).get();
            if (array_of_checked_values != "") {
                $k("#hdnEmpStatusType").val((array_of_checked_values));
            } else {
                $k("#hdnEmpStatusType").val('');
            }
        });
    });

    function getDesignation(deptids) {
        $k("#hdnDesg").val('');
        $k.ajax({
            type: "POST",
            url: 'ajax/getDesignation.php',
            data: "did=" + deptids,
            success: function(data) {
                $k("#desg_nameBox").html(data);
                $k("#selDesignation").multiselect({
                    selectedList: 2,
                    height: '100',
                    minWidth: 'auto',
                    noneSelectedText: ''
                }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
                    var array_of_checked_values = $k("#selDesignation").multiselect("getChecked").map(function() {
                        return this.value;
                    }).get();
                    if (array_of_checked_values != "") {
                        $k("#hdnDesg").val((array_of_checked_values));
                    } else {
                        $k("#hdnDesg").val('');
                    }
                });
            },
            error: function(e) {
                alert("There is somme error in the network. Please try later.");
            }
        });
    }

    function getGrade(levelids) {
        $k("#hdnGrade").val('');
        $k.ajax({
            type: "POST",
            url: 'ajax/getGrade.php',
            data: "lid=" + levelids,
            success: function(data) {
                $k("#gradeBox").html(data);
                $k("#selGrade").multiselect({
                    selectedList: 2,
                    height: '100',
                    minWidth: 'auto',
                    noneSelectedText: ''
                }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
                    var array_of_checked_values = $k("#selGrade").multiselect("getChecked").map(function() {
                        return this.value;
                    }).get();
                    if (array_of_checked_values != "") {
                        $k("#hdnGrade").val((array_of_checked_values));
                    } else {
                        $k("#hdnGrade").val('');
                    }
                });
            },
            error: function(e) {
                alert("There is somme error in the network. Please try later.");
            }
        });
    }

    function getSpecializationGrad(cids) {
        $k("#hdnSpecializationGrad").val('');
        $k.ajax({
            type: "POST",
            url: 'ajax/getSpecializationGrad.php',
            data: "cid=" + cids,
            success: function(data) {
                $k("#specializationGradBox").html(data);
                $k("#selSpecializationGrad").multiselect({
                    selectedList: 2,
                    height: '100',
                    minWidth: 'auto',
                    noneSelectedText: ''
                }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
                    var array_of_checked_values = $k("#selSpecializationGrad").multiselect("getChecked").map(function() {
                        return this.value;
                    }).get();
                    if (array_of_checked_values != "") {
                        $k("#hdnSpecializationGrad").val((array_of_checked_values));
                    } else {
                        $k("#hdnSpecializationGrad").val('');
                    }
                });
            },
            error: function(e) {
                alert("There is somme error in the network. Please try later.");
            }
        });
    }

    function getSpecializationProf(cids) {
        $k("#hdnSpecializationProf").val('');
        $k.ajax({
            type: "POST",
            url: 'ajax/getSpecializationProf.php',
            data: "cid=" + cids,
            success: function(data) {
                $k("#specializationProfBox").html(data);
                $k("#selSpecializationProf").multiselect({
                    selectedList: 2,
                    height: '100',
                    minWidth: 'auto',
                    noneSelectedText: ''
                }).bind("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
                    var array_of_checked_values = $k("#selSpecializationProf").multiselect("getChecked").map(function() {
                        return this.value;
                    }).get();
                    if (array_of_checked_values != "") {
                        $k("#hdnSpecializationProf").val((array_of_checked_values));
                    } else {
                        $k("#hdnSpecializationProf").val('');
                    }
                });
            },
            error: function(e) {
                alert("There is somme error in the network. Please try later.");
            }
        });
    }
</script>
<script type="text/javascript">
    var $k = jQuery.noConflict();
    $k(function() {
        $k("#txtadvanceamount").hide();
        $k("#txtadvanceinstalment").hide();
        $k("#txtapplyfor").change(function() {
            var calType = $k(this).val();
            if (calType == "loan") {
                $k("#txtadvanceamount").hide();
                $k("#txtloanamount").show();
                $k("#txtadvanceinstalment").hide();
                $k("#txtloaninstalment").show();
                $k("#txtloanamount").addClass("required");
                $k("#txtloaninstalment").addClass("required");
                $k("#txtadvanceinstalment").removeClass("required");
                $k("#txtadvanceamount").removeClass("required");
                <?php //if($noofyears < 1){ ?>
                alert("You are not Eligible for apply Loan");
                <?php //} ?>
            } else if (calType == "advance") {
                $k("#txtloanamount").hide();
                $k("#txtadvanceamount").show();
                $k("#txtadvanceinstalment").show();
                $k("#txtloaninstalment").hide();
                $k("#txtadvanceamount").addClass("required");
                $k("#txtadvanceinstalment").addClass("required");
                $k("#txtloanamount").removeClass("required");
                $k("#txtloaninstalment").removeClass("required");
            }
        });

    });
</script>
<script>
    $(".nav li").on("click", function() {
        $(".nav li").removeClass("active");
        $(this).addClass("active");
    });
</script>
<script>
    var currentTime = new Date();
    // First Date Of the month 
    var startDateFrom = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
    // Last Date Of the Month 
    var startDateTo = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);
    var xx = "";
    $(function() {
        $('#dp1').change(function() {
            xx = $('#dp1').val();
        });


        window.prettyPrint && prettyPrint();
        $('#dp1').datepicker({
            maxDate: currentTime,
            format: 'mm/dd/yyyy',
            todayBtn: 'linked',
            onSelect: function() {
                $('#dp2').datepicker('option', 'minDate', $("#dp1").datepicker("getDate"));
            }
        });

        $('#dp2').datepicker({
            minDate: $("#dp1").datepicker("getDate"),
            maxDate: currentTime,
            format: 'mm/dd/yyyy',
            todayBtn: 'linked'
        });
        $('#btn2').click(function(e) {
            e.stopPropagation();
            $('#dp2').datepicker('update', '03/17/12');
        });

        $('#dp3').datepicker();


        var startDate = new Date(2012, 1, 20);
        var endDate = new Date(2012, 1, 25);
        $('#dp4').datepicker()
            .on('changeDate', function(ev) {
                if (ev.date.valueOf() > endDate.valueOf()) {
                    $('#alert').show().find('strong').text('The start date can not be greater then the end date');
                } else {
                    $('#alert').hide();
                    startDate = new Date(ev.date);
                    $('#startDate').text($('#dp4').data('date'));
                }
                $('#dp4').datepicker('hide');
            });
        $('#dp5').datepicker()
            .on('changeDate', function(ev) {
                if (ev.date.valueOf() < startDate.valueOf()) {
                    $('#alert').show().find('strong').text('The end date can not be less then the start date');
                } else {
                    $('#alert').hide();
                    endDate = new Date(ev.date);
                    $('#endDate').text($('#dp5').data('date'));
                }
                $('#dp5').datepicker('hide');
            });

        //inline    
        $('#dp6').datepicker({
            todayBtn: 'linked'
        });

        $('#btn6').click(function() {
            $('#dp6').datepicker('update', '15-05-1984');
        });

        $('#btn7').click(function() {
            $('#dp6').data('datepicker').date = null;
            $('#dp6').find('.active').removeClass('active');
        });
    });
</script>
<script>
    var staticVarWithValue = [];
    staticVarWithValue.push({
        Name: 'income_tax_declaration4',
        Amount: 19200
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration6',
        Amount: 2400
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration8',
        Amount: 15000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration16',
        Amount: 200000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration22',
        Amount: 50000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration24',
        Amount: 25000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration26',
        Amount: 25000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration28',
        Amount: 5000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration32',
        Amount: 50000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration34',
        Amount: 100000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration38',
        Amount: 40000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration40',
        Amount: 60000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration42',
        Amount: 80000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration48',
        Amount: 50000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration52',
        Amount: 50000
    });
    staticVarWithValue.push({
        Name: 'income_tax_declaration54',
        Amount: 100000
    });

    var income_tax_declaration20 = 150000;
    var income_tax_declaration30 = 55000;
    var income_tax_declaration36 = 150000;
    var income_tax_declaration44 = 180000;
    var income_tax_declaration56 = 150000;

    $(document).ready(function() {
        var globalId = "income_tax_declaration";
        var globalIdArray = [];
        for (i = 0; i < 72; i = i + 2) {
            globalIdArray.push({
                X: globalId + (i + 1),
                Y: globalId + (i + 2)
            })
        }
        $.each(globalIdArray, function(index, value) {
            $("#" + value.X).keyup(function() {
                $("#" + value.Y).val(this.value);
                var inputvalue = this.value;
                $.each(staticVarWithValue, function(index1, value1) {
                    if (value.Y == value1.Name) {
                        if (inputvalue < value1.Amount)
                            $("#" + value.Y).val(inputvalue);
                        else
                            $("#" + value.Y).val(value1.Amount);
                    }
                });

            });
        });
        $.each(globalIdArray, function(index, value) {
            $("#" + value.X).change(function() {
                $("#" + value.Y).val(this.value);
                var inputvalue = this.value;
                $.each(staticVarWithValue, function(index1, value1) {
                    if (value.Y == value1.Name) {
                        if (inputvalue < value1.Amount)
                            $("#" + value.Y).val(inputvalue);
                        else
                            $("#" + value.Y).val(value1.Amount);
                    }
                });

            });
        });

    });
    $(document).on("change", ".qty1", function() {
        var sum = 0;
        $(".qty1").each(function() {
            sum += +$(this).val();
        });
        var totalValue = $(".total1").val(sum);
        //alert(totalValue.val());
        if (totalValue.val() < income_tax_declaration20) {
            $(".total1").val(totalValue.val());
            $(".total7").val(totalValue.val());
        } else {
            $(".total7").val(income_tax_declaration20);
        }
    });
    $(document).on("change", ".qty2", function() {
        var sum = 0;
        $(".qty2").each(function() {
            sum += +$(this).val();
        });
        var totalValue = $(".total2").val(sum);
        //alert(totalValue.val());
        if (totalValue.val() < income_tax_declaration30) {
            $(".total2").val(totalValue.val());
            if ($("#income_tax_declaration27").val() > $("#income_tax_declaration28").val()) {
                //alert('hi');
                var sumX = parseFloat($("#income_tax_declaration24").val()) + parseFloat($("#income_tax_declaration26").val()) + parseFloat($("#income_tax_declaration28").val());
                //alert(sumX);
                $(".total8").val(sumX);
            } else {
                $(".total8").val(totalValue.val());
            }
        } else {
            $(".total8").val(income_tax_declaration30);
        }
    });
    $(document).on("change", ".qty3", function() {
        var sum = 0;
        $(".qty3").each(function() {
            sum += +$(this).val();
        });
        var totalValue = $(".total3").val(sum);
        //alert(totalValue.val());
        if (totalValue.val() < income_tax_declaration36) {
            $(".total3").val(totalValue.val());
            $(".total9").val(totalValue.val());
        } else {
            $(".total9").val(income_tax_declaration36);
        }
    });
    $(document).on("change", ".qty4", function() {
        var sum = 0;
        $(".qty4").each(function() {
            sum += +$(this).val();
        });
        var totalValue = $(".total4").val(sum);
        //alert(totalValue.val());
        if (totalValue.val() < income_tax_declaration44) {
            $(".total4").val(totalValue.val());
            $(".total10").val(totalValue.val());
        } else {
            $(".total10").val(income_tax_declaration44);
        }
    });
    $(document).on("change", ".qty5", function() {
        var sum = 0;
        $(".qty5").each(function() {
            sum += +$(this).val();
        });
        var totalValue = $(".total5").val(sum);
        //alert(totalValue.val());
        if (totalValue.val() < income_tax_declaration56) {
            $(".total5").val(totalValue.val());
            $(".total11").val(totalValue.val());
        } else {
            $(".total11").val(income_tax_declaration56);
        }
    });
    $(document).ready(function() {
        //called when key is pressed in textbox
        $("#quantity").keypress(function(e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }
        });
    });
    jQuery('.numbersOnly').keyup(function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $(document).ready(function() {
        $("#income_tax_declaration1").blur(function() {
            if ($(this).val() != '') {
                if ($(this).val() == 0)
                    $("#income_tax_declaration15").removeAttr("disabled");
                else
                    $("#income_tax_declaration15").attr("disabled", "disabled");
            } else {
                $("#income_tax_declaration15").removeAttr("disabled");
            }
        });

        $("#income_tax_declaration15").blur(function() {
            if ($(this).val() != '') {
                if ($(this).val() == 0)
                    $("#income_tax_declaration1").removeAttr("disabled");
                else
                    $("#income_tax_declaration1").attr("disabled", "disabled");
            } else {
                $("#income_tax_declaration1").removeAttr("disabled");
            }
        });
    });
    $(document).ready(function() {
        $("#income_tax_declaration2").blur(function() {
            if ($(this).val() != '')
                $("#income_tax_declaration16").attr("disabled", "disabled");
            else
                $("#income_tax_declaration16").removeAttr("disabled");
        });

        $("#income_tax_declaration16").blur(function() {
            if ($(this).val() != '')
                $("#income_tax_declaration2").attr("disabled", "disabled");
            else
                $("#income_tax_declaration2").removeAttr("disabled");
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#txtEditor").Editor();
    });
</script>
<script>
    $(document).ready(function() {
        $('#imgprint').click(function() {
            $('section#page').print();
        });
        $(window).scroll(function() {
            var fromTop = $(window).scrollTop();
            //alert(fromTop)	;
            if (fromTop > 200)
                $("#floating").css('top', fromTop - 227 + 'px');
            else
                $("#floating").css('top', '0px');
        });

    });
</script>
</body>
</html>