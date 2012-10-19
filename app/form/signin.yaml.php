
user_mail:
  _ui: textbox
  _label: "邮箱账户："
  _filters: "trim"
  _validations:
    - ["is_email", "必须是一个有效的邮箱"]
  class: 'ipt'

user_pass:
  _ui: password
  _label: "账户密码："
  _filters: "trim"
  _validations:
    - ["not_empty", "请输入密码"]
  class: 'ipt'
  autocomplete: 'off'
