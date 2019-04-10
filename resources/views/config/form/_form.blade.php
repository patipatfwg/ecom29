<form id="form-submit" class="form-horizontal" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    @if(isset($payment))
        <input type="hidden" name="config_id" value="{{ isset($payment['id']) ? $payment['id'] : "" }}">
        <input type="hidden" name="_method" value="PUT"/>
    @endif

    <div class="panel">
        <div class="panel-body">
                       

            <div class="row">
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif
                        <div style="position: relative; z-index: 99;">
                              @include('../common._dropdown_right', ['language'=> $language] )
                        </div>
                       
                    <!-- Start: Tab panel -->
                    <div class="tabbable">

                        <!-- Start: Tab menu -->
                        {{--  =================  language   =================  --}}
                        
                          
                        {{--  ================= End language   =================  --}}
                        <!-- End: Tab menu -->

                        <!-- Tab content -->
                        <div class="tab-content">
                            @foreach($language as $lang)
                                <div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
                                    <div class="">

                                        <!-- Name panel -->
                                        @include('config.form.name',[
                                            'language' => $lang,
                                            'name' => isset($payment['option_name'][$lang])? $payment['option_name'][$lang] : ''
                                        ])
                                        <!-- End Name panel -->
                                        <div class="clearfix"></div>
                                        <!-- Name panel -->
                                        @include('config.form.description',[
                                            'language' => $lang,
                                            'name' => isset($payment['description'][$lang])? $payment['description'][$lang] : ''
                                        ])
                                        <!-- End Name panel -->
                                        <div class="clearfix"></div>

                                    </div>
                                </div>
                            @endforeach

                                <!-- bank panel -->
                                 <div class="">
                                    @include('config.form.bank_dropdown',[
                                        'text_name' => 'Bank',
                                        'name' => 'bank',
                                        'value'=> isset($payment['bank_id'])? $payment['bank_id'] : ''
                                    ])
                                    <!-- End bank panel -->
                                    <div class="clearfix"></div>
                                </div>

                                 <div class="">
                                    @include('config.form.input_text',[
                                        'language' => $lang,
                                        'text_name' => 'Cart Threshold',
                                        'name' => 'threshold',
                                        'value'=> isset($payment['cart_threshold'])? $payment['cart_threshold'] : ''
                                    ])
                                    <!-- End Installment panel -->
                                    <div class="clearfix"></div>
                                </div>

                                 <!-- Installment panel -->
                                 <div class="">
                                    @include('config.form.input_text',[
                                        'language' => $lang,
                                        'text_name' => 'Installment Term',
                                        'name' => 'installment',
                                        'value'=> isset($payment['installment_term'])? $payment['installment_term'] : ''
                                    ])
                                    <!-- End Installment panel -->
                                    <div class="clearfix"></div>
                                </div>

                                <!-- Installment panel -->
                                 <div class="">
                                    @include('config.form.input_text',[
                                        'language' => $lang,
                                        'text_name' => 'Interest Rate',
                                        'name' => 'interest_rate',
                                        'value'=> isset($payment['interest_rate'])? $payment['interest_rate'] : ''
                                    ])
                                    <!-- End Installment panel -->
                                    <div class="clearfix"></div>
                                </div>

                                <!-- dateRang panel -->
                                 <div class="">
                                    @include('config.form.datetime',[
                                        'language' => $lang,
                                    ])
                                    <!-- End dateRang panel -->
                                    <br>
                                    <div class="clearfix"></div>
                                </div><br>

                                
                               <!-- Switch -->
                                <!-- dateRang panel -->
                                 <div class="">
                                    @include('config.form._switch',[
                                        'language' => $lang,
                                        'text_name' => 'Status',
                                        'name' => 'status',
                                        'value' => isset($payment['status'])? $payment['status'] : ''

                                    ])
                                    <!-- End dateRang panel -->
                                    <div class="clearfix"></div>
                                </div>
                                <!-- End Switch -->

                                <!-- Switch -->
                                <!-- dateRang panel -->
                                 <div class="">
                                    @include('config.form.applyto',[
                                        'text_name' => 'Applies to',
                                        'products' => isset($products['total'])? $products['total']:0,
                                       
                                    ])
                                    <!-- End dateRang panel -->
                                    <div class="clearfix"></div>
                                </div>
                                <!-- End Switch -->
                                

                        </div>
                        <!-- End Tab content -->

                    </div>
          
                   

                    <!-- Start: Save button -->
                   
                       

                    <!-- End: Save button -->

                    <div class="pull-right">
                        <div class="form-group">
                                    {{ Form::button(' save', [
                                        'type'  => 'submit',
                                        'class' => 'btn bg-primary-800 btn-raised btn-submit'
                                    ]) }}
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>