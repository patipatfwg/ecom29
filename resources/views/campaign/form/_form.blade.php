<form id="form-submit" class="form-horizontal" autocomplete="off">

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                            <div class="col-lg-8">
                                <label class="control-label col-lg-3 text-left">
                                    <span class="text-danger">*</span> Campaign Code :
                                </label>
                                <div class="col-lg-9">
                                    <input class="form-control content-name" name="campaign_code" value="{{ isset($campaignId)? $campaignData['campaign_code'] : '' }}">
                                </div>
                            </div>
                            <!-- Switch -->
                            @include('common._switch', [ 'status' => isset($campaignData) ? $campaignData["status"] : 'inactive' ])
                            <!-- End Switch -->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <!-- Date time picker -->
                            @include('campaign.form.collepeRadio')
                        <!-- End Date time picker -->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <!-- Date time picker -->
                            @include('campaign.form.datetime')
                        <!-- End Date time picker -->
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- Tab container -->
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        {{ Html::ul($errors->all()) }}
                    </div>
                    @endif

                    <!-- Tab panel -->
                    <div class="tabbable panel">

                        <!-- Tab menu -->
                        <ul class="nav nav-tabs bg-teal-400 nav-justified">
                            @foreach($language as $lang)
                            <li class="{{ $lang == $language[0] ? 'active' : '' }}">
                                <a href="#tab-panel-{{ $lang }}" data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <!-- End Tab menu -->

                        <!-- Tab content -->
                        <div class="tab-content">
                            @foreach($language as $lang)
                            <div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
                                <div class="table-responsive">

                                    <!-- Name panel -->
                                    @include('campaign.form.name')
                                    <!-- End Name panel -->

                                    <!-- Description panel -->
                                    @include('campaign.form.description')
                                    <!-- End Description panel-->

                                    <!-- Tag panel -->
                                    @include('campaign.form.tag')
                                    <!-- End Tag panel -->

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- End Tab content -->

                    </div>
                    <!-- End Tab panel -->

                </div>
                <!-- End Tab container -->

                <!-- Seo panel -->
                @include('common._seo' , [
                    'subject'     => isset($campaignData['seo']['title']) ? $campaignData['seo']['title'] : '',
                    'explanation' => isset($campaignData['seo']['description']) ? $campaignData['seo']['description'] : '',
                    'slug'        => isset($campaignData['slug']) ? $campaignData['slug'] : ''
                ])
                <!-- End Seo panel -->
                @include('campaign.form.ribbonList')

                @include('campaign.form.banner')

                @include('common._slug', [ 'slug' => isset($campaignData['slug']) ? $campaignData['slug'] : ''])

                @include('campaign.form.ads_script')
                <!-- Form footer panel -->
                <div class="col-lg-12">
                    <div class="pull-right">
                        <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
                        </div>
                    </div>
                </div>
                <!-- End Form footer panel -->

            </div>
        </div>
    </div>
</form>