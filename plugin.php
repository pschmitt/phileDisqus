<?php

/**
 * Embed disqus in your website
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
    
    public function __construct() {
        \Phile\Event::registerEvent('config_loaded', $this);
        \Phile\Event::registerEvent('before_render_template', $this);
        $this->config = \Phile\Registry::get('Phile_Settings');
    }

    public function on($eventKey, $data = null) {
        if ($eventKey == 'config_loaded') {
            $this->config_loaded();
        } else if ($eventKey == 'before_render_template') {
            $this->export_twig_vars();
        }
    }


	public function config_loaded() {
		if (isset($config['disqus_id'])) {
            $this->disqus_id = $config['disqus_id'];
		}
    }
	
	public function export_twig_vars() {
		if (!empty($this->disqus_id)) {
            if (\Phile\Registry::isRegistered('templateVars')) {
                $twig_vars = \Phile\Registry::get('templateVars');
            } else {
                $twig_vars = array();
            }
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
            \Phile\Registry::set('templateVars', $twig_vars);
		}
    }
}
