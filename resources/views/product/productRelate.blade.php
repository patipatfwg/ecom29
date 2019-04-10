<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Product Relate</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li>
                    <?php
                    $relatedProductSearch = '';
                    if (isset($productIntermediateData['related_product_search']) && $productIntermediateData['related_product_search'] === 'Y') {
                        $relatedProductSearch = 'checked="checked"';
                    } else {
                        if (!isset($productIntermediateData['related_product_search'])) {
                            $relatedProductSearch = 'checked="checked"';
                        }
                    }
                    ?>
                    <label class="control-label margin-right-10">Relate Auto</label>
                    <input <?php echo $relatedProductSearch; ?> type="checkbox" name="related_product_search" class="switch" data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="danger" data-size="mini" />
                </li>
            </ul>
        </div>
	</div>
	<div class="panel-body">
        <div class="col-lg-12">
            <div class="form-group">
                <input placeholder="Search Product ID, Product Name Th, Product Name En" class="form-control" id="product-relate-input" type="text">
                <div class="form-control hide" id="product-relate-list">
                    <div class="product-relate-list-detail"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <table class="table table-border-teal table-striped table-hover datatable-dom-position" id="product-relate-table" data-page-length="10" width="100%">
                    <thead>
                        <tr>
                            <th class="bg-teal-400" width="20">No.</th>
                            <th class="bg-teal-400">item ID</th>
                            <th class="bg-teal-400">Product Name (TH)</th>
                            <th class="bg-teal-400">Product Name (EN)</th>
                            <th class="bg-teal-400" width="80">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($productIntermediateData['related_detail']) && !empty($productIntermediateData['related_detail'])): ?>
                            <?php foreach ($productIntermediateData['related_detail'] as $kRelatedDetail => $vRelatedDetail): ?>
                                <tr data-id="<?php echo $vRelatedDetail['item_id']; ?>">
                                    <td><?php echo $kRelatedDetail+1; ?></td>
                                    <td>
                                        <input name="product_relate[]" value="<?php echo $vRelatedDetail['id']; ?>" type="hidden">
                                        <?php echo $vRelatedDetail['item_id']; ?>
                                    </td>
                                    <td><?php echo $vRelatedDetail['name_th']; ?></td>
                                    <td><?php echo $vRelatedDetail['name_en']; ?></td>
                                    <td class="text-center">
                                        <a class="product-relate-delete"><i class="icon-trash text-danger"></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr data-id="no-data">
                                <td class="text-center" colspan="5">No data.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>