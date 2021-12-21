var useAsync = false;

function hlb_fn_ajaxTransmit_v2( url, data) {
	var calback_id = url.split('/').reverse()[0].replace('.php','');

	$.ajax({url: url
		, type        : "post"
		, data        :  data
		, dataType    : "json"
		, contentType: "application/x-www-form-urlencoded; charset=UTF-8"
		, async        : useAsync
		,success    : function(result, textStatus, data) {
			fn_callBack_v2(calback_id, result, textStatus);
		},
		error    : function(xhr, errorName, error) {
			alert("에러입니다." + xhr.statusText);
		}
	});
	//, contentType: "application/x-www-form-urlencoded; charset=UTF-8"
}

function hlb_fn_file_ajaxTransmit( url, data) {
	var calback_id = url.split('/').reverse()[0].replace('.php','');

	$.ajax({url: url
	   , type        : "post"
	   , data        :  data
	   , dataType    : "json"
	   , contentType: false
	   , processData: false
	   , async        : useAsync
	   ,success    : function(result, textStatus, data) {
		   fn_callBack(calback_id, result, textStatus);
	   },
	   error    : function(xhr, errorName, error) {
		   alert("에러입니다." + xhr.statusText);
	   }
	});
	//, contentType: "application/x-www-form-urlencoded; charset=UTF-8"
}

function hlb_fn_ajaxTransmit( url, data) {
	var calback_id = url.split('/').reverse()[0].replace('.php','');

	$.ajax({url: url
		, type        : "post"
		, data        :  data
		, dataType    : "json"
		, contentType: "application/x-www-form-urlencoded; charset=UTF-8"
		, async        : useAsync
		,success    : function(result, textStatus, data) {
			fn_callBack(calback_id, result, textStatus);
		},
		error    : function(xhr, errorName, error) {
			alert("에러입니다." + xhr.statusText);
		}
	});
	//, contentType: "application/x-www-form-urlencoded; charset=UTF-8"
}

