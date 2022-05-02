$('.select').select2({
			placeholder: 'Seleziona un conto',
			allowClear: true,
			ajax: {
			    url: function (params) {
					return "utils/rest/get-accounts.php";
				},
				
				data: function (params){
					var query = {
						accounts : params.term
					}
					return query;
				},
				
				cache: true,
				processResults: function (data) {
					obj = $.parseJSON(data);
					var categoriesArray = [];
					var accountsArray = [];
					var idex = 1;
					$.each(obj, function(key,value){
						accountsArray = [];
						
						$.each(value, function(key1,value1){
							accountsArray.push({
								id: value1.accounts_id,
								text: value1.accounts_name
							});
						});
						
						categoriesArray.push({
							id: idex++,
							text: key,
							children: accountsArray.slice(0)
						});
						
					});
			
					return {
						results: categoriesArray
					};
				}
			},
			language: {
				noResults: function (params) {
					return "Nessun contro trovato";
				}
			},
		});	