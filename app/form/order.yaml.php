
username:
  _ui: textbox
  _label: "账户："
  _filters: "trim"
  _validations:
    - ["max_length",16,"账户不能超过16个字符"]
    - ["min_length",4,"账户不能少于4个字符"]
    - ["is_alnum","只能是字母和数字"]
  class: 'ipt'

password:
  _ui: password
  _label: "密码："
  _filters: "trim"
  _validations:
    - ["max_length",16,"密码不能超过16个字符"]
    - ["min_length",4,"密码不能少于4个字符"]
    - ["is_alnumu","只能是字母和数字"]
  class: 'ipt'
  autocomplete: 'off'