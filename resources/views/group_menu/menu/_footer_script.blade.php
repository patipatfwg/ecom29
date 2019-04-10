<script type="text/javascript">
    var bannerData = [];
    var i =0;
    @foreach($bannerList as $kData)
    bannerData[i] = {
        id: '{{isset($kData["slug"]) ? $kData["slug"] : ''}}',
        name: '{{$kData["name"]}}',
        image_url: '{{$kData["image_url"]}}?process=resize&resize_width=75&resize_height=75'
    };
    i++;
    @endforeach

    $(document).ready(function() {
        $("#select-type").on('change', function() {
            if ($(this).val() === 'banner') {
                @if(isset($groupHilightData["type"]) && $groupHilightData["type"] != "banner")
                    $('#select2-select-banner-container').html('Select Banner');
                @else
                    $('#select2-select-banner-container').html('Select Banner');
                @endif
            }
            toggleMappingTab();
        });
        toggleMappingTab();
    });

    function toggleMappingTab(){
        var selectedType = $("#select-type").val();
        var tabList = ['link_external','link_internal','campaign','business_category','product_category','banner','content'];
        tabList.forEach(function(entry){
            var id = "#"+entry+"_tab";
            if(selectedType == entry) {
                $(id).find('input').attr('disabled', false);
                $(id).collapse('show');
            } else {
                $(id).find('input').attr('disabled', true);
                $(id).collapse('hide');
            }
        });
    }

    function formatState (state){
        if (!state.id) { return state.name; }

        var $state = $('<span><img src="'+state.image_url+'?process=resize&resize_width=75&resize_height=75" class="img-flag" width="75" height="75"/> '  +state.name + '</span>');
        return $state;
    }

    function formatStateSelection (state){
        if (!state.id) { return state.name; }

        var $state = $('<span><img src="'+state.image_url+'?process=resize&resize_width=25&resize_height=25" class="img-flag" width="25" height="25"/> '  + state.name + '</span>');
        return $state;
    }

    $(".switch").bootstrapSwitch();

    $("#select-banner").select2({
        data: bannerData,
        templateResult: formatState,
        templateSelection: formatStateSelection,
        matcher: matchStart
    }).val('{{ isset($groupHilightData) ? $groupHilightData["value"] : "" }}' ).trigger('change');

    function matchStart (term , text) {
        if(typeof term.term == 'undefined'){
            return text;
        }
        if (text.name.toUpperCase().indexOf(term.term.toUpperCase()) == 0){
            return text;
        }
        return null;
    }

    $("#select-target").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Select target'
    });

    $("#select-type").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Select type'
    });

    $('#select-banner').on('select2:select', function (evt) {
        var slug_banner = $("#select-banner option:selected").val();
        $("#banners").val(slug_banner);
    });

</script>

@include('common._dropdown_script')
@include('common._validate_form_script')

<script type="text/javascript">
    var group_id = '{{ isset($id) ? $id : "" }}';
    var title = '{{$title}}';
    var hilight_id = '{{ isset($hilight_id) ? $hilight_id : "" }}';
    var url = '/group_menu/' + group_id + '/menu';
    var urls = '/group_menu/' + group_id + '/content?title=' + title;
    var createConfig = {
        form: $('#form-submit'),
        url: url + '/add',

        httpMethod: 'POST',
        successCallback: function() {
            window.location = urls
        }
    }

    var updateConfig = {
        form: $('#form-submit'),
        url: url + '/edit_hilight/' + hilight_id ,
        httpMethod: 'PUT',
        successCallback: function() {    
            location.reload();
        }
    }

    var config = (hilight_id === '') ? createConfig : updateConfig;
    validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
</script>
{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
