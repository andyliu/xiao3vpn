
user_mail:
  _ui: textbox
  _label: "邮箱帐号："
  _filters: "trim"
  _validations:
    - ["is_email", "必须是一个有效的邮箱"]
  class: 'ipt'

user_pass:
  _ui: password
  _label: "账户密码："
  _filters: "trim"
  _validations:
    - ["max_length",16,"密码不能超过16个字符"]
    - ["min_length",4,"密码不能少于4个字符"]
    - ["is_alnumu", "只允许字母、数字加下划线"]
  class: 'ipt'
  autocomplete: 'off'

user_pass_checked:
  _ui: password
  _label: "确认密码："
  _filters: "trim"
  _validations:
    - ["max_length",16,"密码不能超过16个字符"]
    - ["min_length",4,"密码不能少于4个字符"]
    - ["is_alnumu", "只允许字母、数字加下划线"]
  class: 'ipt'
  autocomplete: 'off'

services:
  _ui: checkbox
  _label: "服务条款："
  _filters: "trim"
  _validations:
    - ["not_empty","要继续注册，您必须同意本站服务条款"]
