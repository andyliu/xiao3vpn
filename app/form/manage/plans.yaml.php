
name:
  _ui: textbox
  _label: "计划名称："
  _filters: "trim"
  _validations:
    - ["not_empty","计划不能为空"]
    - ["max_length",100,"计划不能超过100个字符"]
  class: 'ipt'

desc:
  _ui: memo
  _label: "计划描述："
  _filters: "trim"
  style: 'padding:3px;width: 574px; height: 74px;'

groupname:
  _ui: dropdownlist
  _label: "Radius组名："
