$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			if(element.attr("name") == "capacity_rate" || element.attr("name") == "hour_rate")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.attr("name") == "positions[]")
			{
				error.addClass('alert alert-danger alert-size');
				error.appendTo( element.parent().next().next() );
			}
			else
			{
				error.addClass('alert alert-danger alert-size')
				error.appendTo( element.parent().next() );
			}
		},
		wrapper: 'div'
	});

    //Dynamic input fields if shift is Sawmill
	$('#place_selects').on('change', function(){
		var place_value = $(this).val();
		if(place_value == "2")
		{
			$('select[name="shift"]').rules('add', {
				ChosenShiftDropdown: true,
				
				messages: {
					ChosenShiftDropdown: "Lūdzu izvēlieties maiņu!"
				}
			});
			$('input[name="capacity_rate"]').rules('add', {
				required: true,
				number: true,
				min: 0,
				max: 999999999999.99,
				step: 0.01,
				IsValidFloatNumber: true,
				IsValidFloatNumberWithTwoDigitsAfterDot: true,
				messages: {
					required: "Lūdzu aizpildiet Kubikmetra likmes lauku!",
					number: "Kubikmetra likme drīkst saturēt tikai ciparus!",
					min: "Kubikmetras likmei jābūt lielākai par nulli!",
					max: "Kubikmetras likme jābūt ne vairāk kā 12 ciparus garam!",
					step: "Kubikmetras likme - maksimums 2 cipari aiz komata!",
					IsValidFloatNumber: "Kubikmetras likmei jābūt lielākai par nulli!",
					IsValidFloatNumberWithTwoDigitsAfterDot: "Kubikmetra likme drīkst saturēt tikai ciparus ar komatu!"
				}
			});
			$('input[name="hour_rate"]').rules('add', {
				required: true,
				number: true,
				min: 0,
				max: 999999999999.99,
				step: 0.01,
				IsValidFloatNumber: true,
				IsValidFloatNumberWithTwoDigitsAfterDot: true,
				messages: {
					required: "Lūdzu aizpildiet Stundas likmes lauku!",
					number: "Stundas likme drīkst saturēt tikai ciparus!",
					min: "Stundas likmei jābūt lielākai par nulli!",
					max: "Stundas likme jābūt ne vairāk kā 12 ciparus garam!",
					step: "Stundas likme - maksimums 2 cipari aiz komata!",
					IsValidFloatNumber: "Stundas likmei jābūt lielākai par nulli!",
					IsValidFloatNumberWithTwoDigitsAfterDot: "Stundas likme drīkst saturēt tikai ciparus ar komatu!"
				}
			});
		}
	});

	$().ready(function(){
		//Validate new employee input form
		$('#employee_form').validate({
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 255,
					IsValidName: true
				},
				last_name: {
					required: true,
					minlength: 3,
					maxlength: 50,
					IsValidName: true
				},
				place: {
					ChosenWorkplaceDropdown: true
				},
				'positions[]': "required",
			},
			messages: {
				name: {
					required: "Lūdzu aizpildiet Vārds lauku!",
					minlength: "Vārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					maxlength: "Vārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					IsValidName: "Vārds drīkst saturēt tikai latīņu burtus!"
				},
				last_name: {
					required: "Lūdzu aizpildiet Uzvārds lauku!",
					minlength: "Uzvārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					maxlength: "Uzvārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					IsValidName: "Uzvārds drīkst saturēt tikai latīņu burtus!"
				},
				place: {
					ChosenWorkplaceDropdown: "Lūdzu izvēlieties darba vietu!"
				},
				'positions[]': "Lūdzu izvēlieties amatu/s!",

			}
		});
	});
});