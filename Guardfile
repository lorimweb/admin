# A sample Guardfile
# More info at https://github.com/guard/guard#readme

# guard 'coffeescript', :input => 'js'

guard :lessc, :in_file => 'assets/less/bootstrap.less', :out_file => 'assets/css/bootstrap.css', :compress => true do
  watch(%r{^.*\.less$})
end

guard :lessc, :in_file => 'assets/less/aplicacao.less', :out_file => 'assets/css/aplicacao.css', :compress => true do
  watch(%r{^.*\.less$})
end

guard :concat, type: "js", files: %w(transition alert button carousel collapse dropdown modal tooltip popover scrollspy tab affix), input_dir: "assets/src_js", output: "assets/src_js/bootstrap.min"
guard :concat, type: "js", files: %w(aplicacao), input_dir: "assets/src_js", output: "assets/src_js/aplicacao.min"

guard :concat, type: "js", files: %w(jquery.validate jquery.mask), input_dir: "assets/src_js", output: "assets/src_js/jquery.plugins.min"


guard 'sprockets', :destination => 'assets/js', :asset_paths => ['assets/src_js'], :root_file => ['bootstrap.min.js', 'aplicacao.min.js', 'jquery.plugins.min.js'], :minify => true do
  watch(%r{^.*\.js$})
end