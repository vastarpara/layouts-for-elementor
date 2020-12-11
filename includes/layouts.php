<div class="lfe-body">
    <div class="lfe-header">
        <h1 class="wp-heading-inline"><?php _e('Layouts for Elementor', LFE_TEXTDOMAIN); ?></h1>
    </div>
    <div id="lfe-wrap" class="lfe-wrap">
        <div class="lfe-header">
            <div class="lfe-title lfe-is-inline"><h2 class="lfe-title"><?php _e('Elementor Template Kits:', LFE_TEXTDOMAIN); ?></h2></div>
            <div class="lfe-sync lfe-is-inline">
                <a href="javascript:void(0);" class="lfe-sync-btn"><?php _e('Sync Now', LFE_TEXTDOMAIN); ?></a>
            </div>
        </div>
        <?php
        $categorys = LFE\API\Layouts_Remote::lfe_get_instance()->categories_list();
        if (!empty($categorys['category']) && $categorys != "") {
            ?>
            <div class="collection-bar">
                <h4><?php _e('Browse by Industry', LFE_TEXTDOMAIN); ?></h4>
                <ul class="collection-list">
                    <li><a class="lfe-category-filter active" data-filter="all" href="javascript:void(0)"><?php _e('All', LFE_TEXTDOMAIN); ?></a></li>
                    <?php
                    foreach ($categorys['category'] as $cat) {
                        ?>
                        <li><a href="javascript:void(0)" class="lfe-category-filter" data-filter="<?php echo $cat['slug']; ?>" ><?php echo $cat['title']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>

        <div class="lfe_wrapper">
            <?php
            $data = LFE\API\Layouts_Remote::lfe_get_instance()->templates_list();
            $i = 0;
            if (!empty($data['templates']) && $data !== "") {
                foreach ($data['templates'] as $key => $val) {
                    $categories = "";
                    foreach ($val['category'] as $ckey => $cval) {
                        $categories .= $cval . " ";
                    }
                    ?>
                    <div class="lfe_box lfe_filter <?php echo sanitize_title($categories); ?>">
                        <div class="lfe_box_widget">
                            <div class="lfe-media">
                                <img src="<?php echo $val['thumbnail']; ?>" alt="screen 1">
                                <?php if ($val['is_pro'] == true) { ?>
                                    <span class="pro-btn"><?php echo _e('PRO', LFE_TEXTDOMAIN); ?></span>
                                <?php } else { ?>
                                    <span class="free-btn"><?php echo _e('FREE', LFE_TEXTDOMAIN); ?></span>
                                <?php } ?>
                            </div>
                            <div class="lfe-template-title"><?php _e($val['title'], LFE_TEXTDOMAIN); ?></div>
                            <div class="lfe-btn">
                                <a href="javascript:void(0)" data-url="<?php echo esc_url($val['url']); ?>" title="<?php _e('Preview', LFE_TEXTDOMAIN); ?>" class="btn pre-btn previewbtn"><?php _e('Preview', LFE_TEXTDOMAIN); ?></a>
                                <a href="javascript:void(0)" title="<?php _e('Install', LFE_TEXTDOMAIN); ?>" class="btn ins-btn installbtn"><?php _e('Install', LFE_TEXTDOMAIN); ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Preview popup div start -->
                    <div class="lfe-preview-popup" id="preview-in-<?php echo $i; ?>">
                        <div class="lfe-preview-container">
                            <div class="lfe-preview-header">
                                <div class="lfe-preview-title"><?php echo $val['title']; ?></div>
                                <div class="lfe-import">
                                    <p class="lfe-msg"><?php _e('Import this template via one click', LFE_TEXTDOMAIN); ?></p>
                                    <span class="lfe-loader"></span>

                                    <a href="javascript:void(0)" class="btn ins-btn lfe-import-btn" disabled data-template-id="<?php echo $val['id']; ?>" ><?php _e('Import Template', LFE_TEXTDOMAIN); ?></a>
                                    <a href="#" class="btn ins-btn lfe-edit-template" style="display:none" target="_blank"><?php _e('Edit Template', LFE_TEXTDOMAIN); ?></a>
                                </div>

                                <span><?php _e('OR', LFE_TEXTDOMAIN); ?></span>

                                <div class="lfe-import lfe-page-create">
                                    <p><?php _e('Create a new page from this template', LFE_TEXTDOMAIN); ?></p>
                                    <input type="text" class="lfe-page-name-<?php echo $val['id']; ?>" placeholder="Enter a Page Name" />
                                    <a href="#" class="btn ins-btn lfe-create-page-btn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Create New Page', LFE_TEXTDOMAIN); ?></a>
                                </div>

                                <span class="lfe-loader-page"></span>

                                <div class="lfe-import lfe-page-edit" style="display:none" >
                                    <p><?php _e('Your template is successfully imported!', LFE_TEXTDOMAIN); ?></p>
                                    <a href="#" class="btn ins-btn lfe-edit-page" target="_blank" ><?php _e('Edit Page', LFE_TEXTDOMAIN); ?></a>
                                </div>
                                <span class="lfe-close-icon"></span>

                                <a href="<?php echo esc_url($val['url']); ?>" class="lfe-dashicons-link" title="<?php _e('Open Preview in New Tab', LFE_TEXTDOMAIN); ?>" rel="noopener noreferrer" target="_blank">
                                    <span class="lfe-dashicons"></span>
                                </a>
                            </div>
                            <iframe width="100%" height="100%" src=""></iframe>
                        </div>
                    </div>
                    <!-- Preview popup div end -->

                    <!-- Install popup div start -->
                    <div class="lfe-install-popup" id="content-in-<?php echo $i; ?>">
                        <div class="lfe-container">
                            <div class="lfe-install-header">
                                <div class="lfe-install-title"><?php echo $val['title']; ?></div>
                                <span class="lfe-close-icon"></span>
                            </div>
                            <div class="lfe-install-content">
                                <p class="lfe-msg"><?php _e('Import this template via one click', LFE_TEXTDOMAIN); ?></p>
                                <div class="lfe-btn">
                                    <span class="lfe-loader"></span>
                                    <a href="javascript:void(0)" class="btn ins-btn lfe-import-btn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Import Template', LFE_TEXTDOMAIN); ?></a>
                                    <a href="#" class="btn ins-btn lfe-edit-template" style="display:none" target="_blank"><?php _e('Edit Template', LFE_TEXTDOMAIN); ?></a>
                                </div>

                                <p class="lfe-horizontal"><?php _e('OR', LFE_TEXTDOMAIN); ?></p>

                                <div class="lfe-page-create">
                                    <p><?php _e('Create a new page from this template', LFE_TEXTDOMAIN); ?></p>
                                    <input type="text" class="lfe-page-<?php echo $val['id']; ?>" placeholder="Enter a Page Name" />
                                    <div class="lfe-btn">
                                        <a href="javascript:void(0)" style="padding: 0;" class="btn pre-btn lfe-create-page-btn" data-name="crtbtn" data-template-id="<?php echo $val['id']; ?>" ><?php _e('Create New Page', LFE_TEXTDOMAIN); ?></a>
                                        <span class="lfe-loader-page"></span>
                                    </div>
                                </div>
                                <div class="lfe-create-div lfe-page-edit" style="display:none" >
                                    <p style="color: #000;"><?php _e('Your page is successfully imported!', LFE_TEXTDOMAIN); ?></p>
                                    <div class="lfe-btn">
                                        <a href="#" class="btn pre-btn lfe-edit-page" target="_blank" ><?php _e('Edit Page', LFE_TEXTDOMAIN); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Install popup div end -->
                    <?php
                    $i++;
                }
            } else {
                echo $data['message'];
            }
            ?>
        </div>
    </div>
</div>