<?php
class Custom_Rich_Text_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'custom_rich_text_widget',
            'Custom Rich Text',
            array('description' => 'A simple widget with a title and rich text body.')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        if (!empty($instance['text'])) {
            echo '<div class="widget-rich-text-body">' . wpautop(wp_kses_post($instance['text'])) . '</div>';
        }
        
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $text  = !empty($instance['text']) ? $instance['text'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">Content (HTML allowed):</label>
            <textarea class="widefat" rows="6" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_textarea($text); ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['text']  = wp_kses_post($new_instance['text']);
        return $instance;
    }
}
function wpb_widgets_init()
{
    register_widget('Custom_Rich_Text_Widget');

    register_sidebar(array(
        'name' => 'Footer Text',
        'id' => 'custom-footer-text-widget',
        'before_widget' => '<div class="cftw-widget">',
        'after_widget' => '</div>',
    ));
    
    register_sidebar(array(
        'name'          => 'Footer 1 Widget',
        'id'            => 'custom-footer1-widget',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h5 class="font-weight-bold text-uppercase mb-4">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => 'Footer 2 Widget',
        'id'            => 'custom-footer2-widget',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h5 class="font-weight-bold text-uppercase mb-4">',
        'after_title'   => '</h5>',
    ));

    register_sidebar(array(
        'name'          => 'Footer 3 Widget',
        'id'            => 'custom-footer3-widget',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h5 class="font-weight-bold text-uppercase mb-4">',
        'after_title'   => '</h5>',
    ));
    /*
    register_sidebar(array(
        'name' => 'Block',
        'id' => 'custom-block-widget',
        'before_widget' => '<div class="cfbw-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="bold text-uppercase mb-4">',
        'after_title' => '</h5>',
    ));

    register_sidebar(array(
        'name' => 'Block2',
        'id' => 'custom-block2-widget',
        'before_widget' => '<div class="cfb2w-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="bold text-uppercase mb-4">',
        'after_title' => '</h5>',
    ));
   
    register_sidebar(array(
        'name'          => 'Global CTA Footer',
        'id'            => 'custom-cta-footer-widget',
        'before_widget' => '<div class="cff-widget">',
        'after_widget'  => '</div>',
    ));
    */
}
add_action('widgets_init', 'wpb_widgets_init');
