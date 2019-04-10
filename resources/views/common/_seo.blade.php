<div class="col-lg-12">
    <div class="panel">

        <!-- Header panel -->
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title font-bold">SEO Setting</h6>
        </div>
        <!-- End Header panel -->

        <div class="panel-body">

            <!-- Seo detail -->
            <div class="row">
                <div class="col-lg-12">
                    <h6
                        id="seo_subject_show{{ isset($id)? '-'.$id : '' }}"
                        class="panel-title font-bold seo-subject"
                        >{{ !empty($subject)? $subject : 'This is an Example of a Title Tag that is Eighty Characters in Length' }}</h6>
                        <div id="seo_url" class="seo-url">
                            <?php
                            $nameRoute = explode('.', Route::current()->getName());
                            $nameRouteReplace = str_replace('category_business', 'category', $nameRoute[0]);
                            echo (isset($slug) && !empty($slug)) ? $nameRouteReplace . '/<span>' . $slug . '</span>' : $nameRouteReplace . '/<span>slug</span>' ;
                            ?>
                        </div>
                    <div class="panel-title ckeditor-display-text break-word" id="seo_explanation_show{{ isset($id)? '-'.$id : '' }}">
                        {{ !empty($explanation)? $explanation : "Here is an example of what a snippet looks like in Google's SERPs The content that appears here is usually taken from the Meta Description tag if relevant." }}
                    </div>
                </div>
            </div>
            <!-- End Seo detail -->

            <!-- Edit seo icon -->
            <div class="text-right" data-toggle="collapse" data-target="#seo_collapse{{ isset($id)? '-'.$id : '' }}">
                <a class="text-underline">Edit Settings</a>
            </div>
            <!-- End Edit seo icon -->

            <!-- Seo edit panel -->
            <div class="collapse {{ empty($subject)? 'in' : '' }}" id="seo_collapse{{ isset($id)? '-'.$id : '' }}">

                <!-- Subject panel -->
                <div class="seo_edit_panel">
                    <div class="row margin-top-10">
                        <div class="col-lg-3">
                            <label for="subjectInputName">Title Page</label>
                        </div>
                    <div class="col-lg-9">
                        <input
                            id="subjectInputName{{ isset($id)? '-'.$id : '' }}"
                            type="text"
                            class="form-control"
                            name="seo_subject{{ isset($id)? '-'.$id : '' }}"
                            maxlength="80"
                            @if(isset($readOnly))
                                {{ ($readOnly)? 'disabled' : '' }}
                            @endif
                            value="{{ isset($subject)? strip_tags($subject) : '' }}"
                            placeholder="No longer than 80 characters">
                    </div>
                </div>
                <!-- End Subject panel -->

                <!-- Explanation panel -->
                <div class="row margin-top-10">
                    <div class="col-lg-3">
                        <label for="explanationInput">META Description</label>
                    </div>
                    <div class="col-lg-9">
                        <textarea
                            id="explanationInput{{ isset($id)? '-'.$id : '' }}"
                            class="form-control padding-right-30"
                            rows="3"
                            name="seo_explanation{{ isset($id)? '-'.$id : '' }}"
                            maxlength="170"
                            @if(isset($readOnly))
                                {{ ($readOnly)? 'disabled' : '' }}
                            @endif
                            placeholder="No longer than 170 characters"
                            >{{ isset($explanation)? strip_tags($explanation) : '' }}</textarea>
                    </div>
                </div>
                <!-- End Explanation panel -->

            </div>
            <!-- End Seo edit panel -->

        </div>
    </div>
</div>
