<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Makro Item ID : </label>
      <div class="col-lg-8">
         <label class="control-label col-lg-12 text-left">{{ isset($data['item_id'])? $data['item_id']:'' }}</label>
      </div>
   </div>
</div>
@include('common._switch', [ 'status' => ($data['published_status']=='Y')? 'on' : 'off' ])
<div class="col-lg-12">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Approve Satatus : </label>
      <div class="col-lg-8">
         <!-- Default select -->
         <select class="form-control" data-width="100%" name="approve_status">
         <option value="new sync" @if(isset($data['approve_status'])) {{ $data['approve_status'] == 'new sync' ? 'selected':'' }} @endif >New Sync</option>
         <option value="waiting" @if(isset($data['approve_status'])) {{ $data['approve_status'] == 'waiting' ? 'selected':'' }} @endif >Waiting</option>
         <option value="approved" @if(isset($data['approve_status'])) {{ $data['approve_status'] == 'approved' ? 'selected':'' }} @endif >Approve</option>
         </select>
         <!-- /default select -->
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-2 text-left">priority : </label>
      <div class="col-lg-2">
         <input type="text" class="col-lg-12 form-control" name="priority" value="{{ isset($data['priority'])?$data['priority']:'' }}">
      </div>
   </div>
</div>
<div class="col-lg-12">
   <div class="form-group">
      <label class="control-label col-lg-2 text-left">Normal Price : </label>
      <div class="col-lg-2">
         <label class="control-label col-lg-9 text-left">{{ isset($data['normal_price'])?$data['normal_price']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-12">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Barcode  : </label>
      <div class="col-lg-8">
         <label class="control-label col-lg-12 text-left">{{ isset($data['barcode'])?$data['barcode']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Brand : </label>
      <div class="col-lg-8">
         <label class="control-label col-lg-12 text-left">{{ isset($data['brand_id'])?$data['brand_id']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Supplier  : </label>
      <div class="col-lg-8">
         <label class="control-label col-lg-12 text-left">{{ isset($data['supplier_id'])?$data['supplier_id']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-3 text-left">Unit type : <span class="control-label text-danger"> *</span> </label>
      <div class="col-lg-2">
         <input type="text" class="col-lg-12 form-control" name="unit_value" value="{{ isset($data['unit_value'])?$data['unit_value']:'' }}">
      </div>
      <div class="col-lg-4">
         <select name="unit_type" class="form-control">
         <option value="piece" @if(isset($data['unit_type'])) {{ $data['unit_type'] == 'piece' ? 'selected' : '' }} @endif >Piece</option>
         <option value="pack" @if(isset($data['unit_type'])) {{ $data['unit_type'] == 'pack' ? 'selected' : '' }} @endif  >Pack</option>
         <option value="gram" @if(isset($data['unit_type'])) {{ $data['unit_type'] == 'gram' ? 'selected' : '' }} @endif >Gram</option>
         <option value="kilogram" @if(isset($data['unit_type'])) {{ $data['unit_type'] == 'kilogram' ? 'selected' : '' }} @endif >Kilogram</option>
         </select>
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-3 text-left">Piece : <span class="text-danger"> *</span> </label>
      <div class="col-lg-8">
         <input type="text" name="price" class="form-control" value="{{ isset($data['price'])?$data['price']:'' }}">
      </div>
   </div>
</div>
<div class="col-lg-4">
   <div class="form-group">
      <label class="control-label col-lg-5 text-left">Suggest Price : </label>
      <div class="col-lg-5">
         <input type="text" name="suggest_price" class="form-control" value="{{ isset($data['suggest_price'])?$data['suggest_price']:'' }}">
      </div>
   </div>
</div>
<div class="col-lg-4">
   <div class="form-group">
      <label class="control-label col-lg-5 text-left">Profit per unit : </label>
      <div class="col-lg-5">
         <input type="text" name="profit_per_unit" class="form-control" value="{{ isset($data['profit_per_unit'])?$data['profit_per_unit']:'' }}">
      </div>
   </div>
</div>
<div class="col-lg-4">
   <div class="form-group">
      <label class="control-label col-lg-5 text-left">Total Profit : </label>
      <div class="col-lg-5">
         <input type="text" name="total_profit" class="form-control" value="{{ isset($data['total_profit'])?$data['total_profit']:'' }}">
      </div>
   </div>
</div>
<div class="col-lg-12">
   <div class="form-group">
      <label class="control-label col-lg-2 text-left">Assortment type : <span class="text-danger">*</span></label>
      <div class="col-lg-2 control-label">
         <input type="radio" name="assortment_type" @if(isset($data['assortment_type'])) {{ $data['assortment_type'] == 'assortment' ? 'checked="checked"':'' }} @endif  value="assortment"> Assortment
      </div>
      <div class="col-lg-4 control-label">
         <input type="radio" name="assortment_type" @if(isset($data['assortment_type'])) {{ $data['assortment_type'] == 'deal_service' ? 'checked="checked"':'' }} @endif value="non_assortment"> Non Assortment 
         <button class="btn bg-primary btn-xs">Supplier List</button>
      </div>
      <div class="col-lg-2 control-label">
         <input type="radio" name="assortment_type" @if(isset($data['assortment_type'])) {{ $data['assortment_type'] == 'deal_service' ? 'checked="checked"':'' }} @endif value="deal_service" > Deal & Service
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">E-Commerce Buyer : </label>
      <div class="col-lg-4">
         <label class="control-label col-lg-12 text-left">{{ isset($data['buyer_id'])?$data['buyer_id']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-6">
   <div class="form-group">
      <label class="control-label col-lg-4 text-left">Makro Buyer  : </label>
      <div class="col-lg-4">
         <label class="control-label col-lg-12 text-left">{{ isset($data['buyer_id'])?$data['buyer_id']:'' }}</label>
      </div>
   </div>
</div>
<div class="col-lg-12">
   <div class="form-group">
      <label class="control-label col-lg-2 text-left">Payment : <span class="text-danger">*</span></label>
      <div class="col-lg-2 control-label">
         <input type="checkbox" name="payment[]" @if(isset($data['payment'])) {{ strpos($data['payment'], 'pay@store')!== false?'checked':'' }} @endif value="pay@store"> Pay @ Store
      </div>
      <div class="col-lg-2 control-label">
         <input type="checkbox" name="payment[]" @if(isset($data['payment'])) {{ strpos($data['payment'], 'tmn-creditcard')!== false?'checked':'' }} @endif value="tmn-creditcard"> TMN-CreditCard
      </div>
      <div class="col-lg-2 control-label">
         <input type="checkbox" name="payment[]" @if(isset($data['payment'])) {{ strpos($data['payment'], 'wallet')!== false?'checked':'' }} @endif value="wallet"> Wallet
      </div>
      <div class="col-lg-2 control-label">
         <input type="checkbox" name="payment[]" @if(isset($data['payment'])){{ strpos($data['payment'], 'installment')!== false?'checked':'' }} @endif value="installment"> Installment
      </div>
   </div>
</div>
<input type="hidden" name="have_image" value="{{ $data['have_image'] }}">
<input type="hidden" name="have_detail" value="{{ $data['have_detail'] }}">
<input type="hidden" name="have_categories" value="{{ $data['have_categories'] }}">
<input type="hidden" name="last_flag" value="{{ $data['last_flag'] }}">