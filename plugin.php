<?php

/**
 * Embed disqus in your website
 * Requires jquery
 * 
 * @package Phile
 * @subpackage PhileDisqus
 * @version 1.0
 * @author Philipp Schmitt <philipp@schmitt.co>
 * @license http://opensource.org/licenses/GPL-3.0
 * @link https://github.com/pschmitt/phileDisqus
 * @link http://philecms.github.io/Phile
 */
class PhileDisqus extends \Phile\Plugin\AbstractPlugin implements \Phile\EventObserverInterface {

    private $config;
    private $disqus_id;
    private $onclick;

    public function __construct() {
        \Phile\Event::registerEvent('config_loaded', $this);
        \Phile\Event::registerEvent('before_render_template', $this);
        $this->config = \Phile\Registry::get('Phile_Settings');
        
        // init
        $this->onclick = true;
    }

    public function on($eventKey, $data = null) {
        if ($eventKey == 'config_loaded') {
            $this->config_loaded();
        } else if ($eventKey == 'before_render_template') {
            $this->export_twig_vars();
        }
    }

    public function config_loaded() {
        // merge the arrays to bind the settings to the view
        // Note: this->config takes precedence
        $this->config = array_merge($this->settings, $this->config);

        if (isset($this->config['disqus_id'])) {
            $this->disqus_id = $this->config['disqus_id'];
        }
        if (isset($this->config['disqus_load_on_click'])) {
            $this->onclick = isset($this->config['disqus_load_on_click']);
        }
    }

    public function export_twig_vars() {
        // Don't do anything if disqus id missing
        if (empty($this->disqus_id)) return;

        if (\Phile\Registry::isRegistered('templateVars')) {
            $twig_vars = \Phile\Registry::get('templateVars');
        } else {
            $twig_vars = array();
        }

        if ($this->onclick) {
            // http://internet-inspired.com/wrote/load-disqus-on-demand/
            $twig_vars['disqus_comments'] = '
            <!-- An element a visitor can click if they <3 comments! -->
            <div id="show-comments-wrapper">
                <a class="show-comments" href="javascript:void(0)">Load Disqus comments</a>
                <!--button class="show-comments">Load Disqus comments</button-->
            </div>
            <!-- The empty element required for Disqus to loads comments into -->
            <div id="disqus_thread"></div>
            <script>
                // Requires jQuery of course.
                $(document).ready(function() {
                    $(".show-comments").on("click", function() {
                        var disqus_shortname = "' . $this->disqus_id . '";
                        // ajax request to load the disqus javascript
                        $.ajax({
                            type: "GET",
                            url: "http://" + disqus_shortname + ".disqus.com/embed.js",
                            dataType: "script",
                            cache: true
                        });
                        // hide the button once comments load
                        $(this).fadeOut();
                    });
                });
            </script>
            ';
        } else {
            $twig_vars['disqus_comments'] = '
            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = \''. $this->disqus_id .'\';

                /* * * DON\'T EDIT BELOW THIS LINE * * */
                (function() {
                    var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;
                    dsq.src = \'//\' + disqus_shortname + \'.disqus.com/embed.js\';
                    (document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
            ';
        }
        \Phile\Registry::set('templateVars', $twig_vars);
    }
}
