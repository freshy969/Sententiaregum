Exec { path => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/local/node/node-default/bin' }
Class['::apt::update'] -> Package <|
  title != 'python-software-properties'
  and title != 'software-properties-common'
|>

hiera_include('classes')
