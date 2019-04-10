<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title">Ribbons</h6>
        </div>
        
        <div class="col-lg-12 margin-bottom-20">
            
                <div class="col-lg-12">
                    @foreach($ribbonData as $ribbon)
                        <div class="col-lg-3 margin-top-20">
                            <input type="radio" name="ribbon" value="{{ $ribbon['id'] }}"
                            @if(isset($campaignData))
                                @if($campaignData['ribbon']['id'] == $ribbon['id'])
                                    checked
                                @endif
                            @endif > 

                            @if($ribbon['type']=='static')
                                <img class="margin-left-10" src="{{ $ribbon['image']['medium'] }}?process=resize&resize_width=60&resize_height=60" width="60px">
                            @elseif($ribbon['type']=='no_ribbon')
                                <b class="margin-left-10">{{$ribbon['name']}}</b>
                            @endif
                        </div>

                    @endforeach
                </div>   
        </div>
        <div class="clearfix"></div>   
    </div>
</div>