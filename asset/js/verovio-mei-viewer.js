/* Original mei-viewer javascript */

/* General init */

var verovioId = 'verovio';
var verovioDiv = document.getElementById(verovioId);

var vrvToolkit = new verovio.toolkit();
var page = 1;
var zoom = 40;
var pageHeight = 2970;
var pageWidth = 2100;
var swipe_pages = false;
var format = 'mei';
var outputFilename = 'output.mei'
var ids = [];
var pdfFormat = "A4";
var pdfOrientation = "portrait";
var savedOptions = undefined;
var customOptions = undefined;
var target = "";
var disabledOptions = ["adjustPageHeight", "breaks", "landscape", "pageHeight", "pageWidth", "mmOutput", "noFooter"];

// reload cookies
if ( $.cookie('zoom') ) zoom = $.cookie('zoom');
if ( $.cookie('pdfFormat') ) pdfFormat = $.cookie('pdfFormat');
if ( $.cookie('pdfOrientation') ) pdfOrientation = $.cookie('pdfOrientation');

if (localStorage['customOptions']) {
    customOptions = JSON.parse(localStorage['customOptions']);
}

function calc_page_height() {
    return ($(document).height() - $( "#navbar" ).height() - 4) * 100 / zoom;
}

function calc_page_width() {
    return ($(".row-offcanvas").width()) * 100 / zoom ; // - $( "#sidbar" ).width();
}

function set_options( ) {
    pageHeight = calc_page_height();
    pageWidth = calc_page_width();
    options = {
        adjustPageHeight: "true",
        breaks: "auto",
        inputFormat: format,
        mmOutput: "false",
        noFooter: "true",
        pageHeight: pageHeight,
        pageWidth: pageWidth,
        scale: zoom
    };

    if (customOptions !== undefined) {
        localStorage['customOptions'] = JSON.stringify(customOptions);
        var mergedOptions = {};
        for(var key in customOptions) mergedOptions[key] = customOptions[key];
        for(var key in options) mergedOptions[key] = options[key];
        options = mergedOptions;
    }

    //console.log( options );
    vrvToolkit.setOptions( options );
    //vrvToolkit.setOptions( mergedOptions );
}

function upload_file() {

    format = 'mei';
    $("#mei_download").hide();
    $('#submit-btn').popover('hide');
    var f = $("#mei_files").prop('files')[0];
    var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        outputFilename = theFile.name;
        return function(e) {
            $('.row-offcanvas').toggleClass('active');
            load_data(e.target.result);
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsText(f);
};

function load_data(data) {
    set_options();

    console.time("loading");
    try {
        vrvToolkit.loadData(data);

        if (vrvToolkit.getPageCount() == 0) {
            $("#verovio").hide().css("cursor", "auto");
            log = vrvToolkit.getLog();
            $('#err').text(log).html();
            $('#errorDialog').modal();
        }
        else {
            $("#total_text").html(vrvToolkit.getPageCount());
            page = 1;
            var pageTarget = 0;
            if (target != "") {
                pageTarget = vrvToolkit.getPageWithElement(target.substr(1));
                if (pageTarget != 0) {
                    page = pageTarget;
                }
            }
            load_page();
            if (pageTarget != 0) {
                $(target).attr("fill", "#d00").attr("stroke", "#d00");
            }
            $("#play-button").show();
            $("#pdf-button").show();
            $("#options-button").show();
        }
    }
    catch(err) {
        $("#verovio").hide().css("cursor", "auto");
        $('#err').html(err);
        $('#errorDialog').modal();
    }
    console.timeEnd("loading");
}

function load_page() {
    $("#verovio").hide().css("cursor", "auto");
    $("#jump_text").val(page);

    svg = vrvToolkit.renderToSVG(page, {});
    $("#svg_output").html(svg);

    adjust_page_height();
};

function next_page() {
    if (page >= vrvToolkit.getPageCount()) {
        return;
    }

    page = page + 1;
    load_page();
};

function prev_page() {
    if (page <= 1) {
        return;
    }

    page = page - 1;
    load_page();
};

function first_page() {
    page = 1;
    load_page();
};

function last_page() {
    page = vrvToolkit.getPageCount();
    load_page();
};

function apply_zoom() {
    console.log("apply zoom");
    // make sure we have loaded a file
    if (vrvToolkit.getPageCount() == 0) return;

    $.cookie('zoom', zoom, { expires: 30 });
    set_options();
    var measure = 0;
    if (page != 1) {
        measure = $("#svg_output .measure").attr("id");
    }

    vrvToolkit.redoLayout();

    $("#total_text").html(vrvToolkit.getPageCount());
    page = 1;
    if (measure != 0) {
        page = vrvToolkit.getPageWithElement(measure);
    }
    load_page();
}

function zoom_out() {
    if (zoom < 20) {
        return;
    }

    zoom = zoom / 2;
    apply_zoom();
}

function zoom_in() {
    if (zoom > 80) {
        return;
    }

    zoom = zoom * 2;
    apply_zoom();
}

function do_page_enter(e) {
    key = e.keyCode || e.which;
    if (key == 13) {

        text = $("#jump_text").val();

        if (text <= vrvToolkit.getPageCount() && text > 0) {
            page = Number(text);
            load_page();
        } else {
            $("#jump_text").val(page);
        }

    }
}

function do_zoom_enter(e) {
    key = e.keyCode || e.which;
    if (key == 13) {
        text = $("#zoom_text").val();
        zoom_val = Number(text.replace("%", ""));
        if (zoom_val < 10) zoom_val = 10;
        else if (zoom_val > 160) zoom_val = 160;
        zoom = zoom_val;
        apply_zoom();
    }
}

function adjust_page_height() {
    // adjust the height of the panel
    if ( $('#svg_panel svg') ) {
        zoomed_height = pageHeight * zoom / 100;
        if ( zoomed_height < $('#svg_panel svg').height() ) {
            zoomed_height = $('#svg_panel svg').height();
        }
        $('#svg_output').height( zoomed_height ); // slighly more for making sure we have no scroll bar
        //$('#svg_panel svg').height(pageHeight * zoom / 100 );
        //$('#svg_panel svg').width(pageWidth * zoom / 100 );
    }

    // also update the zoom control
    $("#zoom_text").val(zoom + "%");

    // enable the swipe (or not)
    enable_swipe( ( $('#svg_panel svg') && ( $('#svg_panel svg').width() <= $('#svg_panel').width() ) ) );
}

function swipe_prev(event, direction, distance, duration, fingerCount) {
      prev_page();
 }

 function swipe_next(event, direction, distance, duration, fingerCount) {
     next_page();
 }

 function swipe_zoom_in(event, target) {
     zoom_in();
 }

 function swipe_zoom_out(event, target) {
     zoom_out();
 }

function enable_swipe( pages ) {
    if ( pages && !swipe_pages ) {
        $("#svg_output").swipe( "destroy" );
        $("#svg_output").swipe( { swipeLeft: swipe_next, swipeRight: swipe_prev, tap: swipe_zoom_in, doubleTap: swipe_zoom_out, allowPageScroll:"auto"} );
        swipe_pages = true;
    }
    // zoom only
    else if ( !pages && swipe_pages ) {
        $("#svg_output").swipe( "destroy" );
        $("#svg_output").swipe( { tap: swipe_zoom_in, doubleTap: swipe_zoom_out, allowPageScroll:"auto"} );
        swipe_pages = false;
    }
}

function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

function play_midi() {
    var base64midi = vrvToolkit.renderToMIDI();
    var song = 'data:audio/midi;base64,' + base64midi;
    $("#player").show();
    $("#play-button").hide();
    $("#player").midiPlayer.play(song);
}

var midiUpdate = function(time) {
    var vrvTime = Math.max(0, time - 400);
    var elementsattime = vrvToolkit.getElementsAtTime(vrvTime);
    if (elementsattime.page > 0) {
        if (elementsattime.page != page) {
            page = elementsattime.page;
            load_page();
        }
        if ((elementsattime.notes.length > 0) && (ids != elementsattime.notes)) {
            ids.forEach(function(noteid) {
                if ($.inArray(noteid, elementsattime.notes) == -1) {
                    $("#" + noteid ).attr("fill", "#000");
                    $("#" + noteid ).attr("stroke", "#000");
                    //$("#" + noteid ).removeClassSVG("highlighted");
                }
            });
            ids = elementsattime.notes;
            ids.forEach(function(noteid) {
                if ($.inArray(noteid, elementsattime.notes) != -1) {
                //console.log(noteid);
                    $("#" + noteid ).attr("fill", "#c00");
                    $("#" + noteid ).attr("stroke", "#c00");;
                    //$("#" + noteid ).addClassSVG("highlighted");
                }
            });
        }
    }
}

var midiStop = function() {
    ids.forEach(function(noteid) {
        $("#" + noteid ).attr("fill", "#000");
        $("#" + noteid ).attr("stroke", "#000");
        //$("#" + noteid ).removeClassSVG("highlighted");
    });
    $("#player").hide();
    $("#play-button").show();
}

$.fn.addClassSVG = function(className){
    $(this).attr('class', function(index, existingClassNames) {
        return existingClassNames + ' ' + className;
    });
    return this;
};

$.fn.removeClassSVG = function(className){
    $(this).attr('class', function(index, existingClassNames) {
        //var re = new RegExp(className, 'g');
        //return existingClassNames.replace(re, '');
    });
    return this;
};

function generate_pdf() {

    var pdfFormat = $("#pdfFormat").val();
    var pdfSize = [2100, 2970];
    if (pdfFormat == "letter") pdfSize = [2159, 2794];
    else if (pdfFormat == "B4") pdfSize = [2500, 3530];

    var pdfOrientation = $("#pdfOrientation").val();
    var pdfLandscape = pdfOrientation == 'landscape';
    var pdfHeight = pdfLandscape ? pdfSize[0] : pdfSize[1];
    var pdfWidth = pdfLandscape ? pdfSize[1] : pdfSize[0];

    var fontCallback = function(family, bold, italic, fontOptions) {
        if (family == "VerovioText") {
            return family;
        }
        if (family.match(/(?:^|,)\s*sans-serif\s*$/) || true) {
            if (bold && italic) {return 'Times-BoldItalic';}
            if (bold && !italic) {return 'Times-Bold';}
            if (!bold && italic) {return 'Times-Italic';}
            if (!bold && !italic) {return 'Times-Roman';}
        }
    };

    var options = {};
    options.fontCallback = fontCallback;

    var doc = new PDFDocument({useCSS: true, compress: true, autoFirstPage: false, layout: pdfOrientation});
    var stream = doc.pipe(blobStream());

    stream.on('finish', function() {
        var blob = stream.toBlob('application/pdf');
        var pdfFilename = outputFilename.replace(/\.[^\.]+$/, '.pdf');
        saveAs(blob, pdfFilename);
    });

    var buffer = Uint8Array.from(atob(vrvTTF), c => c.charCodeAt(0));
    doc.registerFont('VerovioText',buffer);

    pdfOptions = {
        adjustPageHeight: false,
        breaks: "auto",
        mmOutput: true,
        noFooter: false,
        pageHeight: pdfHeight,
        pageWidth: pdfWidth,
        scale: 100
    }

    vrvToolkit.setOptions(pdfOptions);
    vrvToolkit.redoLayout();
    for (i = 0; i < vrvToolkit.getPageCount(); i++) {
        doc.addPage({size: pdfFormat, layout: pdfOrientation});
        SVGtoPDF(doc, vrvToolkit.renderToSVG(i + 1, {}), 0, 0, options);
    }

    // Reset the options
    set_options();
    vrvToolkit.redoLayout();

    doc.end();
}

function non_default_options (options) {
    var defaultOptions = vrvToolkit.getOptions(true);
    var nonDefaultOptions = {};
    for(var key in defaultOptions) {
        if (options[key] && (defaultOptions[key] != options[key])) {
            nonDefaultOptions[key] = options[key];
        }
    }
    return nonDefaultOptions;
}

/* Modal dialog for options */

options = vrvToolkit.getAvailableOptions();

var ul = $("<ul class='nav nav-tabs' role='tablist'></ul>");
$('#option-div').append(ul);

var form = $("<form class='form-horizontal' id='option-form'></form>");
$('#option-div').append(form);
var tab_content = $("<div class='tab-content'></div>");
form.append(tab_content);

var i = 0;
for (grp in options.groups) {
    var li = $("<li role='presentation'></li>");
    ul.append(li);
    if (i == 0) {
        li.attr("class", "active");
    }

    var a = $("<a></a>").attr("href", "#" + grp).attr("aria-controls", grp).attr("role", "tab").attr("data-toggle", "tab");
    li.append(a);
    a.append(options.groups[grp].name);

    var tab_panel = $("<div role='tabpanel'></div>");
    tab_content.append(tab_panel);
    if (i == 0) {
        tab_panel.attr("class", "tab-pane active panel-body");
    }
    else {
        tab_panel.attr("class", "tab-pane panel-body");
    }
    tab_panel.attr("id", grp);

    for (opt in options.groups[grp].options) {

        var option = options.groups[grp].options[opt];

        var form_group = $("<div class='form-group'></div>");
        tab_panel.append(form_group);

        var label = $("<label class='col-sm-3 control-label'></label>");
        form_group.append(label);
        label.attr("for", opt);
        label.append(option.title);

        var input_div = $("<div class='col-sm-3'></div>");
        form_group.append(input_div);

        if ((option.type == "double") || (option.type == "int") || (option.type == "std::string") || (option.type == "array")) {

            var input = $("<input class='form-control'></input>");
            input_div.append(input);
            input.attr("name", opt);

            if ((option.type == "double") || (option.type == "int")) {
                input.attr("type", "number");
                input.attr("min", option.min);
                input.attr("max", option.max);
                if (option.type == "double") {
                    input.attr("step", "0.05");
                }
                else {
                    input.attr("step", "1");
                }
            }
            else {
                input.attr("type", "text");
            }
        }
        else if (option.type == "std::string-list") {
            var select = $("<select class='form-control'></select>");
            input_div.append(select);
            select.attr("name", opt);

            for (v in option.values) {
                var opt_select = $("<option></option>");
                select.append(opt_select);
                opt_select.attr("value", option.values[v]);
                opt_select.append(option.values[v]);
            }
        }
        else if (option.type == "bool") {
            var input = $("<input class='form-control' style='width: auto !important;' type='checkbox'></input>");
            input_div.append(input);
            input.attr("name", opt);
        }

        var help_div = $("<div class='col-sm-6'></div>");
        form_group.append(help_div);

        var help_p = $("<p class='help-block'></p>");
        help_div.append(help_p);
        help_p.append().text(option.description).html();

    }

    i = i + 1;

}

/* More general functions and events. */

$( document ).ready(function() {

    var version = vrvToolkit.getVersion();
    var n = version.lastIndexOf('-');
    var commit = version.substring(n + 1);
    $('#version').text( version );
    $("#version").attr( "href", "http://github.com/rism-ch/verovio/commit/" + commit ) ;

    $(window).keyup(function(event){
        // We need to make sure not to capture event on text fields
        if ( $(event.target).hasClass('form-control') ) {
            return;
        }
        if ( event.ctrlKey && (event.keyCode == 37) ) {
            first_page();
        }
        else if ( event.keyCode == 37 ) {
            prev_page();
        }
        else if ( event.ctrlKey && (event.keyCode == 39) ) {
            last_page();
        }
        else if ( event.keyCode == 39 ) {
            next_page();
        }
        else if ( event.keyCode == 107 ) {
            zoom_in();
        }
        else if ( event.keyCode == 109 ) {
            zoom_out();
        }
    });

    $(window).resize(function(){
        apply_zoom();
    });

    $( "#toggle_log" ).click(function() {
        log = !log;
        $( "#log_panel_body" ).toggle();
        // toggle icon
        $("span", this).toggleClass("glyphicon-chevron-down glyphicon-chevron-left");
    });

    $( "#mei_download" ).click(function() {
        outputFilename = outputFilename.replace(/\.[^\.]+$/, '.mei');
        saveAs(new Blob([vrvToolkit.getMEI(-1, true)], {type: "text/xml;charset=utf-8"}), outputFilename);
    });

    // Set the popover for the btn
    $( ".popover-btn" ).popover( );

    // Adjust the size of the svg_output and the zoom according to the div (screen) size
    width = $('#svg_panel').width();

    zoom = Math.min( Math.floor( 100 * width / 2100 ), zoom );

    // Init the swipe
    enable_swipe( true );

    // Load the default file or the file passed in the URL
    var file = verovioDiv.getAttribute('data-url');
    if (!file) {
        file = getParameterByName("file");
    }
    var musicxml = getParameterByName("musicxml");
    var local = getParameterByName("local");

    if (musicxml == "true") {
        format = 'musicxml';
        $("#mei_download").show();
    }

    if (local == "true") {
        data = localStorage.getItem("musicxml_file");
        outputFilename = localStorage.getItem("musicxml_filename");
        load_data( data );
    }
    else {
        if (!file || (file.length == 0)) {
            file = "examples/downloads/Chopin_Etude_op.10_no.9.mei";
        }
        else {
            target = window.location.hash;
        }
        $("#verovio").show();
        $.ajax({
            url: file
            , dataType: "text"
            , success: function(data) {
                outputFilename = file.replace(/^.*[\\\/]/, '');
                load_data( data );
            }
        });
    }
    $(".row-offcanvas").on('transitionend webkitTransitionEnd oTransitionEnd mozTransitionend MSTransitionEnd', function() {
        if (pageWidth != calc_page_width()) {
            apply_zoom();
        }
    });

    $("#player").midiPlayer({
        color: "#c00",
        onUpdate: midiUpdate,
        onStop: midiStop,
        width: 250
    });

    $("#pdfFormat").val(pdfFormat);
    $("#pdfOrientation").val(pdfOrientation);

    $('#pdfOK').click(function () {
        $('#pdfDialog').modal('hide');
        var pdfFormat = $("#pdfFormat").val();
        var pdfOrientation = $("#pdfOrientation").val();
        $.cookie('pdfFormat', pdfFormat, { expires: 30 });
        $.cookie('pdfOrientation', pdfOrientation, { expires: 30 });
        generate_pdf();
    });

    for (opt in disabledOptions) {
        $("#option-form :input[name='" + disabledOptions[opt] + "']").prop("disabled", true);
    }

    $("#option-form :input").change(function() {
        // Reset all options to default
        vrvToolkit.setOptions(vrvToolkit.getOptions(true));
        // console.log("Applying options");
        customOptions = non_default_options(JSON.parse($('#option-form').toJSON()));
        apply_zoom();
    });

    $("#optionDialog").on("show.bs.modal", function () {
        savedOptions = non_default_options(vrvToolkit.getOptions(false));
        var currentOptions = vrvToolkit.getOptions(false)
        var defaultOptions = vrvToolkit.getOptions(true);
        for(var key in defaultOptions) {

            if (disabledOptions.includes(key)) {
                continue;
            }

            if (!Array.isArray(defaultOptions[key]) && (defaultOptions[key] != currentOptions[key])) {
                $("label[for='" + key +"']").css("color", "#B00");
            }
            else {
                $("label[for='" + key +"']").css("color", "");
            }
        }

        $("#option-form").fromJSON(JSON.stringify(currentOptions));
    });

    $("#optionDialog").on("hidden.bs.modal", function () {
        // This means the dialog was dismissed
        console.log(savedOptions);
        if (savedOptions !== undefined) {
            customOptions = savedOptions;
            savedOptions = undefined;
            //console.debug("Restoring options");
        }
        apply_zoom();
    });

    $('#optionOK').click(function () {
        savedOptions = undefined;
        $('#optionDialog').modal('hide');
    });

    $('#optionDefault').click(function () {
        savedOptions = undefined;
        customOptions = undefined;
        // Reset to default and clear local storage
        vrvToolkit.setOptions(vrvToolkit.getOptions(true));
        localStorage.clear('customOptions');
        //console.debug("Resetting options");
        $('#optionDialog').modal('hide');
    });
});

$(document).ready(function() {
    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });
});
