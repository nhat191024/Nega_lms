import 'package:nega_lms/utils/imports.dart';

class CustomTextField extends StatelessWidget {
  final String labelText;
  final Color labelColor;
  final double labelSize;
  final TextInputType? keyboardType;
  final double leftPadding;
  final double rightPadding;
  final double topPadding;
  final double bottomPadding;
  final double border;
  final String hintText;
  final Color hintTextColor;
  final String errorText;
  final RxBool isError;
  final bool needErrorText;
  final RxBool obscureText;
  final TextEditingController controller;
  final Function(String) onChanged;
  final Widget? suffixIcon;
  final VoidCallback? onPress;
  final bool? isRequire;
  final Color backgroundColor;
  final String? suffixText;
  final double? width;

  const CustomTextField({
    super.key,
    required this.labelText,
    this.labelColor = CustomColors.secondaryText,
    this.labelSize = 14,
    this.keyboardType,
    this.leftPadding = 20,
    this.rightPadding = 20,
    this.topPadding = 0,
    this.bottomPadding = 0,
    this.border = 5,
    required this.hintText,
    this.hintTextColor = CustomColors.disable,
    required this.errorText,
    required this.isError,
    this.needErrorText = true,
    required this.obscureText,
    required this.controller,
    required this.onChanged,
    this.suffixIcon,
    this.onPress,
    this.isRequire = false,
    this.backgroundColor = CustomColors.background,
    this.suffixText,
    this.width,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(leftPadding, topPadding, rightPadding, bottomPadding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          RichText(
            text: TextSpan(
              text: labelText,
              style: TextStyle(
                fontSize: labelSize,
                fontFamily: FontStyleTextStrings.bold,
                color: labelColor,
              ),
              children: isRequire == true
                  ? [
                      const TextSpan(
                        text: '*',
                        style: TextStyle(
                          color: Colors.red,
                        ),
                      ),
                    ]
                  : [],
            ),
          ),
          const SizedBox(height: 5),
          SizedBox(
            width: width,
            child: Obx(
              () => TextField(
                obscureText: obscureText.value,
                keyboardType: keyboardType,
                controller: controller,
                onChanged: (value) {
                  onChanged(value);
                },
                decoration: InputDecoration(
                  hintText: hintText,
                  hintStyle: TextStyle(color: hintTextColor),
                  contentPadding: const EdgeInsets.fromLTRB(15, 15, 10, 15),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(border),
                    borderSide: BorderSide(
                      color: isError.value ? CustomColors.errorMain : CustomColors.dividers,
                      width: 1,
                    ),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(14),
                    borderSide: BorderSide(
                      color: isError.value ? CustomColors.errorMain : CustomColors.dividers,
                      width: 1,
                    ),
                  ),
                  errorText: needErrorText
                      ? isError.value
                          ? errorText
                          : null
                      : null,
                  focusedBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: isError.value ? CustomColors.errorMain : CustomColors.primaryText, width: 1),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  filled: backgroundColor == CustomColors.background ? isError.value : true,
                  fillColor: isError.value ? CustomColors.errorLight : backgroundColor,
                  suffixIcon: suffixIcon != null ? IconButton(onPressed: onPress, icon: suffixIcon!) : null,
                  suffix: Text(
                    suffixText ?? '',
                    style: const TextStyle(color: CustomColors.primaryText, fontSize: 16, fontFamily: FontStyleTextStrings.regular),
                  ),
                  alignLabelWithHint: true,
                  isDense: true,
                  suffixIconConstraints: const BoxConstraints(
                    minWidth: 0,
                    minHeight: 0,
                  ),
                  floatingLabelBehavior: FloatingLabelBehavior.always,
                ),
              ),
            ),
          )
        ],
      ),
    );
  }
}

class SearchTextField extends GetView<SearchTextFieldController> {
  final TextEditingController textController;
  final Function(String) onSearch;
  final String hintText;
  final Color color;
  final Color prefixColor;

  const SearchTextField({
    super.key,
    required this.textController,
    required this.onSearch,
    this.hintText = 'Search...',
    this.color = CustomColors.background,
    required this.prefixColor,
  });

  @override
  Widget build(BuildContext context) {
    // Initialize controller if not done already
    Get.put(SearchTextFieldController());

    return Obx(() => Container(
          decoration: BoxDecoration(
            color: controller.isFocused.value ? Colors.white : color,
            borderRadius: BorderRadius.circular(50),
            border: Border.all(
              color: controller.isFocused.value ? Colors.black : Colors.transparent,
              width: 1,
            ),
          ),
          child: TextField(
            controller: textController,
            focusNode: controller.focusNode,
            onChanged: (value) {
              controller.updateText(value);
              onSearch(value);
            },
            decoration: InputDecoration(
              prefixIcon: SvgPicture.asset(
                Images.searchIcon,
                height: 15,
                width: 15,
                fit: BoxFit.scaleDown,
                colorFilter: ColorFilter.mode(prefixColor, BlendMode.srcIn),
              ),
              suffixIcon: Obx(() => controller.hasText.value
                  ? IconButton(
                      icon: const Icon(
                        Icons.cancel,
                        size: 20,
                        color: CustomColors.secondaryText,
                      ),
                      onPressed: () {
                        textController.clear();
                        controller.clearText();
                        onSearch('');
                      },
                    )
                  : const SizedBox.shrink()),
              hintText: hintText,
              hintStyle: const TextStyle(
                color: CustomColors.secondaryText,
              ),
              border: InputBorder.none,
              contentPadding: const EdgeInsets.symmetric(vertical: 15, horizontal: 10),
            ),
          ),
        ));
  }
}

class SearchTextFieldController extends GetxController {
  final focusNode = FocusNode();
  final isFocused = false.obs;
  final hasText = false.obs;

  @override
  void onInit() {
    super.onInit();
    focusNode.addListener(_onFocusChange);
  }

  @override
  void onClose() {
    focusNode.removeListener(_onFocusChange);
    focusNode.dispose();
    super.onClose();
  }

  void _onFocusChange() {
    isFocused.value = focusNode.hasFocus;
  }

  void updateText(String value) {
    hasText.value = value.isNotEmpty;
  }

  void clearText() {
    hasText.value = false;
  }
}

class CheckBoxField extends StatelessWidget {
  final String title;
  final RxBool value;
  final Color activeColor;
  final double textSize;
  final String fontFamily;
  final Function(bool? newValue) onChanged;
  final double leftPadding;
  final double rightPadding;
  final double topPadding;
  final double bottomPadding;

  const CheckBoxField({
    super.key,
    required this.title,
    required this.value,
    this.activeColor = CustomColors.primary,
    this.textSize = 14,
    this.fontFamily = FontStyleTextStrings.regular,
    required this.onChanged,
    this.leftPadding = 0,
    this.rightPadding = 0,
    this.topPadding = 0,
    this.bottomPadding = 0,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(leftPadding, topPadding, rightPadding, bottomPadding),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Obx(
            () => Transform.scale(
              scale: 1.4,
              child: Checkbox(
                value: value.value,
                onChanged: (bool? newValue) {},
                activeColor: activeColor,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(5),
                ),
                side: const BorderSide(
                  width: 1.5,
                  color: CustomColors.border,
                ),
              ),
            ),
          ),
          const SizedBox(width: 10),
          Text(
            title,
            style: TextStyle(fontSize: textSize, color: CustomColors.primaryText, fontFamily: fontFamily),
          ),
        ],
      ),
    );
  }
}

class SelectBox extends StatelessWidget {
  final String labelText;
  final Color labelColor;
  final double labelSize;
  final double leftPadding;
  final double rightPadding;
  final double topPadding;
  final double bottomPadding;
  final double border;
  final String hintText;
  final Color hintTextColor;
  final String errorText;
  final RxBool isError;
  final bool needErrorText;
  final bool? isRequire;
  final Color backgroundColor;
  final double? width;
  final List<DropdownMenuItem<dynamic>> items;
  final dynamic value;
  final Function(dynamic) onChanged;

  const SelectBox({
    super.key,
    required this.labelText,
    this.labelColor = CustomColors.secondaryText,
    this.labelSize = 14,
    this.leftPadding = 20,
    this.rightPadding = 20,
    this.topPadding = 0,
    this.bottomPadding = 0,
    this.border = 5,
    required this.hintText,
    this.hintTextColor = CustomColors.disable,
    required this.errorText,
    required this.isError,
    this.needErrorText = true,
    this.isRequire = false,
    this.backgroundColor = CustomColors.background,
    this.width,
    required this.items,
    required this.value,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(leftPadding, topPadding, rightPadding, bottomPadding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          RichText(
            text: TextSpan(
              text: labelText,
              style: TextStyle(
                fontSize: labelSize,
                fontFamily: FontStyleTextStrings.bold,
                color: labelColor,
              ),
              children: isRequire == true
                  ? [
                      const TextSpan(
                        text: '*',
                        style: TextStyle(
                          color: Colors.red,
                        ),
                      ),
                    ]
                  : [],
            ),
          ),
          const SizedBox(height: 5),
          SizedBox(
            width: width,
            child: Obx(
              () => DropdownButtonFormField(
                value: value,
                items: [
                  DropdownMenuItem(
                    value: null,
                    child: Text(
                      hintText,
                      style: TextStyle(
                        color: hintTextColor,
                        fontSize: 14,
                        fontFamily: FontStyleTextStrings.regular,
                      ),
                    ),
                  ),
                  ...items,
                ],
                onChanged: onChanged,
                style: const TextStyle(
                  color: CustomColors.primaryText,
                  fontSize: 14,
                  fontFamily: FontStyleTextStrings.regular,
                ),
                decoration: InputDecoration(
                  hintText: hintText,
                  hintStyle: TextStyle(color: hintTextColor),
                  contentPadding: const EdgeInsets.fromLTRB(15, 15, 10, 15),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(border),
                    borderSide: BorderSide(
                      color: isError.value ? CustomColors.errorMain : CustomColors.dividers,
                      width: 1,
                    ),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(14),
                    borderSide: BorderSide(
                      color: isError.value ? CustomColors.errorMain : CustomColors.dividers,
                      width: 1,
                    ),
                  ),
                  errorText: needErrorText
                      ? isError.value
                          ? errorText
                          : null
                      : null,
                  focusedBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: isError.value ? CustomColors.errorMain : CustomColors.primaryText, width: 1),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  filled: backgroundColor == CustomColors.background ? isError.value : true,
                  fillColor: isError.value ? CustomColors.errorLight : backgroundColor,
                ),
                icon: const Icon(Icons.arrow_drop_down),
                isExpanded: true,
                dropdownColor: Colors.white,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
