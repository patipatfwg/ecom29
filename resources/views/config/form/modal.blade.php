

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MANAGE BANK</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                        {!! Form::open([
                            'autocomplete' => 'off',
                            'class'        => 'form-horizontal',
                            'id'           => 'search-form'
                        ]) !!}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {{ Form::text('full_text', null, [
                                        'id'          => 'full_text',
                                        'class'       => 'form-control',
                                        'placeholder' => 'Bank Name'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="row">
                                {{ Form::button('<i class="icon-search4"></i> Search', [
                                    'type'  => 'submit',
                                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                                ]) }}
                            </div>
                        {!! Form::close() !!}
                </div>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <table class="table table-border-gray table-striped datatable-dom-position" id="bank-table"  width="100%">
                        <thead>
                            <tr>
                            
                                <th width="150">Bank Name (TH)</th>
                                <th width="150">Bank Name (EN)</th>
                                <th width="20">Logo</th>
                                <th width="20">Fee</th>
                                <th width="20">Status</th>               
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" class="text-center">
                                    <i class="icon-spinner2 spinner"></i> Loading ...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <ul class="pagination">
                   
                </ul>
            </div>
        </div>
    </div>
</div>
