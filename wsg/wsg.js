// progress list
function progressList() {
    var table = $("#progressList,#progressList02,#progressList03,#progressList04,#progressList05,#progressList06");

    // line number
    table.find("tr td:first-child").each(function(i, v) {
        $(v).text(i + 1);
    });

    var stateFields = table.find("td.state");

    stateFields.each(function(i, v) {
        var current = $(v);
        var fields = current.parent().children();

        var prop = {
            // type: current.is(":last-child") && "http://validator.w3.org/check?uri=" + "http://211.253.28.191:6080/00_web"+ "/html" || "../html",
            type: "../html",
            directory: fields.eq(-3).text(),
            pageId: fields.eq(-2).text()
        };

        var wrapAnchor = $("<a>")
            .attr("target", "_blank")
            .attr("href", prop.type + "/" + prop.directory + "/" + prop.pageId + ".html")
            .text(v.textContent);

        if (current.text() == "미정") {
            current.addClass("undecided");
        } else if (current.text() == "진행") {
            current.addClass("working");
        } else if (current.text() == "완료") {
            current.addClass("complete");
        } else if (current.text() == "검수") {
            current.addClass("modify");
        } else if (current.text() == "삭제") {
            current.addClass("del");
        } else if (current.text() == "검증") {
            current.addClass("validation");
        } else if (current.text() == "수정요청") {
            current.addClass("apply");
        } else {
			current.addClass("modify");
		}
        current.html(wrapAnchor);
    });
}




/**
 * String.trim()
 * trim method가 없는 경우 구현해줌
 */
if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '');
    };
}

/**
 * 예제소스를 표시하기 위한 HtmlEditor를 관리한다
 * editor는 CodeMirror를 사용함
 * @type {{codeMirror: Array, create: Function}}
 * @see http://codemirror.net/
 */
var HtmlEditor = {
    /**
     * 생성한 codemirror editor의 object를 저장한다
     */
    codeMirror: [],

    /**
     * editor를 생성한다
     * target과 preview selector는 선택된 대상의 수가 서로 같아야한다
     * @param target (required) 소스코드가 있는 대상 컨테이너
     * @param preview (optional) 소스코드의 미리보기가 나타날 대상
     */
    create: function(target, preview) {
        preview = $(preview);

        $(target).each(function(i, v) {
            v.codeMirror = HtmlEditor.codeMirror[i] = new CodeMirror(function(elt) {
                $(v).replaceWith(elt); // 소스코드 원본이 있는 컨테이너를 editor로 replace
            }, {
                mode: "text/html",
                lineNumbers: true,
                value: v.innerText.trim(),
                theme: "eclipse",
                indentWithTabs: true,
                tabSize: 4,
                indentUnit: 4
            });

            $(v).remove();

            // 미리보기 대상이 없는 경우 패스
            if (preview.length <= 0) {
                return;
            }

            v.codeMirror.preview = preview.eq(i);
            v.codeMirror.preview.html(v.codeMirror.getValue());

            v.codeMirror.on("change", function() { // 소스코드가 변경된 경우 preview에 출력
                clearTimeout(v.codeMirror.timer);
                v.codeMirror.timer = setTimeout(function() {
                    v.codeMirror.preview.html(v.codeMirror.getValue());
                }, 300);
            });
        });

    }
};




// call function
jQuery(function() {

        progressList();

});

$(document).ready(function() {
    // 탭
    $('.tab li a').each(function(){

        var this_href=$(this).attr('href');
        $(this_href).css('display','none').siblings('.tab-cont').first().css('display','block');
        $(this).click(function(e){

            e.preventDefault();
            //탭활성화
            $(this).parent('li').addClass('active').siblings('li').removeAttr('class');
            //타켓 디스플레이
            $(this_href).css('display','block').siblings('.tab-cont').css('display','none');
            // 프레임 사이즈 조절
            //mainFrameResize();
        });
    });
});

