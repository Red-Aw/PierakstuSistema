$(function(){
    jQuery.validator.setDefaults({

		wrapper: 'div'
	});

	$().ready(function(){
		//Validate sawmill production input form
		$('#sawmill_form').validate({
					errorPlacement: function(error, element) {
			if(element.attr("name") == "time_from" || element.attr("name") == "time_to")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.attr("name") == "maintenance_times[]")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().next().next().next() );
			}
			else if(element.attr("name") == "maintenance_notes[]")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().next().next() );
			}
			else if(element.attr("name") == "working_hours[]" || element.attr("name") == "nonworking[]")
			{
				//error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent() );
			}
			else
			{
				error.addClass('alert alert-danger alert-size');
				error.appendTo( element.parent().next() );
			}		
		},
			rules: {
				date: {
					required: true,
					date: true,
					IsValidDate: true,
				},
				time_from: {
					required: true,
					IsValidTime: true,
				},
				time_to: {
					required: true,
					IsValidTime: true,
				},
				invoice: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					remote: {
						url: "check_invoice",
						type: "post"
					},
				},
				beam_count: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				sizes: {
					required: true,
				},
				lumber_count: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				lumber_capacity: {
					required: true,
					number: true,
					min: 0,
					max: 999999999999.999,
					step: 0.001,
					IsValidFloatNumber: true,
					IsValidFloatNumberWithThreeDigitsAfterDot: true,
				},
				note: {
					required: false,
					minlength: 3,
					maxlength: 50,
					IsValidText: true,
				},
				"maintenance_times[]": {
					required: function(element){
						return $('input[name="maintenance_notes[]"]').val() != "";
					},
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				"maintenance_notes[]": {
					required: function(element){
						return $('input[name="maintenance_times[]"]').val() != "";
					},
					minlength: 3,
					maxlength: 255,
					IsValidText: true,
				},
				shifts: {
					required: true,
				},
				"working_hours[]": {
					required: function(element){
						return $('select[name="nonworking[]"]').val() == '';
					},
					number: true,
					min: 1,
					max: 24,
					//IsValidHours: true,
				},
				"nonworking[]": {
					required: function(element){
						return $('input[name="working_hours[]"]').val() == "";
					},
				},
			},
			messages: {
				date: {
					required: "Lūdzu aizpildiet Datums lauku!",
					date: "Lūdzu ievadiet korektu datumu (GGGG-MM-DD vai GGGG-MM-DD)!",
					IsValidDate: "Lūdzu ievadiet korektu datumu (GGGG-MM-DD vai GGGG-MM-DD)!",
				},
				time_from: {
					required: "Lūdzu aizpildiet 'Laiks no' lauku!",
					IsValidTime: "Lūdzu ievadiet korektu laiku, formā: hh:mm!",
				},
				time_to: {
					required: "Lūdzu aizpildiet 'Laiks līdz' lauku!",
					IsValidTime: "Lūdzu ievadiet korektu laiku, formā: hh:mm!",
				},
				invoice: {
					required: "Lūdzu aizpildiet Pavadzīmes Nr. lauku!",
					number: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
					min: "Pavadzīmes Nr. jābūt lielākam par nulli!",
					max: "Pavadzīmes Nr. jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
					remote: "Pavadzīme ar šādu numuru jau eksistē!",
				},
				beam_count: {
					required: "Lūdzu aizpildiet 'Apaļkoku skaits' lauku!",
					number: "Apaļkoku skaits drīkst saturēt tikai ciparus!",
					min: "Apaļkoku skaitam jābūt lielākam par nulli!",
					max: "Apaļkoku skaitam jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Apaļkoku skaits drīkst saturēt tikai ciparus!",
				},
				sizes: {
					required: "Lūdzu izvēlieties kubatūras izmēru",
				},
				lumber_count: {
					required: "Lūdzu aizpildiet 'Zāģmateriālu skaits' lauku!",
					number: "Zāģmateriālu skaits drīkst saturēt tikai ciparus!",
					min: "Zāģmateriālu skaitam jābūt lielākam par nulli!",
					max: "Zāģmateriālu skaitam jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Zāģmateriālu skaits drīkst saturēt tikai ciparus!",
				},
				lumber_capacity: {
					required: "Lūdzu aizpildiet 'Zāģmateriālu skaits' lauku!",
					number: "Zāģmateriālu skaits drīkst saturēt tikai ciparus!",
					min: "Zāģmateriālu skaitam jābūt lielākam par nulli!",
					max: "Zāģmateriālu skaitam jābūt ne vairāk kā 12 ciparus garam!",
					step: "Maksimums 3 cipari aiz komata",
					IsValidFloatNumber: "Zāģmateriālu skaitam jābūt lielākam par nulli!",
					IsValidFloatNumberWithThreeDigitsAfterDot: "Zāģmateriālu skaits drīkst saturēt tikai ciparus ar komatu",
				},
				note: {
					minlength: "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!",
					maxlength: "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!",
					IsValidText: "Citas piezīmes drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!", 
				},
				"maintenance_times[]": {
					required: "Lūdzu aizpildiet 'Minūtes' lauku!",
					number: "Remonta minūtes drīkst saturēt tikai ciparus!",
					min: "Remonta minūtem jābūt lielākam par nulli!",
					max: "Remonta minūtem jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Remonta minūtes drīkst saturēt tikai ciparus!",
				},
				"maintenance_notes[]": {
					required: "Lūdzu aizpildiet 'Piezīmes' lauku!",
					minlength: "Remonta piezīmei jābūt garumā no 3 simboliem līdz 255 simboliem!",
					maxlength: "Remonta piezīmei jābūt garumā no 3 simboliem līdz 255 simboliem!",
					IsValidText: "Remonta piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!",
				},
				shifts: {
					required: "Lūdzu izvēlieties maiņu!",
				},
				"working_hours[]": {
					required: "Lūdzu aizpildiet tikai vienu ievadlauku katram darbiniekam!",
					number: "Nostrādātās stundas drīkst sastāvēt tikai no cipariem!",
					min: "Nostrādātām stundām jābūt lielākām par nulli!",
					max: "Nostrādātās stundas nevar būt vairāk par 24",
					IsValidHours: "Nostrādātās stundas drīkst sastāvēt tikai no cipariem!",
				},
				"nonworking[]": {
					required: "Lūdzu aizpildiet tikai vienu ievadlauku katram darbiniekam!",
				},
			}
		});
	});
});