
user_mail:
  _ui: textbox
  _label: "邮箱账户："
  class: 'ipt'

user_pass:
  _ui: password
  _label: "账户密码："
  _filters: "trim"
  _validations:
    - ["skip_empty"]
    - ["max_length",16,"密码不能超过16个字符"]
    - ["min_length",4,"密码不能少于4个字符"]
  class: 'ipt'
  autocomplete: 'off'


user_pass_checked:
  _ui: password
  _label: "确认密码："
  _filters: "trim"
  _validations:
    - ["skip_empty"]
    - ["max_length",16,"密码不能超过16个字符"]
    - ["min_length",4,"密码不能少于4个字符"]
  class: 'ipt'
  autocomplete: 'off'
