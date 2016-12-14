/**
 * Created by max_2 on 8/30/2016.
 */

function submitInputData(){
    $('#results').css("opacity", "0").removeClass('fail').removeClass('success');	
	$('div#graphic').css("opacity", "0");
	$('div#graphic img.math_plot').attr('src', '');
    $('#wrapper div#processing').css('opacity', '1');
    var calcServiceAddress = "http://calc-deque.rhcloud.com/RESTfulExample_war";
	//var calcServiceAddress = "http://localhost:8080";
    var encodedStr = ($('.str').val().replace(/\//g, '<slash>'));
    var XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;
    var xhr = new XHR();
    xhr.open('GET', calcServiceAddress + '/str/' + encodedStr, true);
    xhr.onload = function() {
		console.log(this.responseText);
        if(this.status == 200) {	
			var obj = JSON.parse(this.responseText);			
			
			if (this.responseText.indexOf('MathPlotError') !== -1){			
				alert(obj.MathPlotError);
			}else if (obj.MathPlotFileName !== 'null'){
				$('div#graphic').css('opacity', '1');
				$('div#graphic img.math_plot').attr('src', calcServiceAddress + '/resources/images/' + obj.MathPlotFileName);
			}
			
            $('#results').text('$$Input: ' + obj.MathFunc + '$$ $$Result: ' + obj.MathResultFunc + '$$').addClass('success').css('opacity', '1');
			
            var math = document.getElementById("results");
            MathJax.Hub.Queue(["Typeset",MathJax.Hub,math]);
        }else {
            $('#results').addClass('fail').text('Status: ' + this.status + '; Error: ' + this.responseText).css('opacity', '1');
        }
        $('#wrapper div#processing').css('opacity', '0');
    };
    xhr.onerror = function() {
        $('#results').text('Status: ' + this.status + '; Error: ' + this.responseText).addClass('.fail').css('opacity', '1');
        $('#wrapper div#processing').css('opacity', '0');
    }
    xhr.send();
}