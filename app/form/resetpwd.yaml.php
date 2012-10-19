user_mail:
  _ui: textbox
  _label: "邮箱帐号："
  _filters: "trim"
  _validations:
    - ["is_email", "必须是一个有效的邮箱"]
  class: 'ipt'