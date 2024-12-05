import 'package:nega_lms/utils/imports.dart';

class CustomButton extends StatelessWidget {
  final bool isDisabled;
  final VoidCallback onTap;
  final String btnText;
  final double textSize;
  final Color? textColor;
  final String fontFamily;
  final double? width;
  final double? height;
  final Color btnColor;
  final Color borderColor;
  final double leftPadding;
  final double topPadding;
  final double rightPadding;
  final double bottomPadding;
  final double borderRadius;
  final IconData? prefixIcon;
  final Color? prefixIconColor;
  final double? prefixIconSize;
  final ImageProvider? prefixImage;
  final double? prefixImageWidth;
  final double? prefixImageHeight;
  final Color? prefixImageColor;
  final String? prefixSvgImage;
  final double? prefixSvgImageWidth;
  final double? prefixSvgImageHeight;
  final Color? prefixSvgImageColor;
  final double? prefixTextGap;
  final IconData? suffixIcon;
  final Color? suffixIconColor;
  final double? suffixIconSize;
  final ImageProvider? suffixImage;
  final double? suffixImageWidth;
  final double? suffixImageHeight;
  final Color? suffixImageColor;
  final String? suffixSvgImage;
  final double? suffixSvgImageWidth;
  final double? suffixSvgImageHeight;
  final Color? suffixSvgImageColor;

  const CustomButton({
    super.key,
    this.isDisabled = false,
    required this.onTap,
    required this.btnText,
    this.textSize = 16,
    this.textColor = CustomColors.white,
    this.fontFamily = FontStyleTextStrings.regular,
    this.width = 120,
    this.height,
    this.btnColor = CustomColors.primary,
    this.borderColor = CustomColors.primary,
    this.leftPadding = 0,
    this.topPadding = 0,
    this.rightPadding = 0,
    this.bottomPadding = 0,
    this.borderRadius = 50,
    this.prefixIcon,
    this.prefixIconColor = CustomColors.white,
    this.prefixIconSize,
    this.prefixImage,
    this.prefixImageWidth = 24,
    this.prefixImageHeight = 24,
    this.prefixSvgImage,
    this.prefixImageColor,
    this.prefixSvgImageWidth = 24,
    this.prefixSvgImageHeight = 24,
    this.prefixSvgImageColor,
    this.prefixTextGap = 8,
    this.suffixIcon,
    this.suffixIconColor = CustomColors.white,
    this.suffixIconSize,
    this.suffixImage,
    this.suffixImageWidth = 24,
    this.suffixImageHeight = 24,
    this.suffixSvgImage,
    this.suffixImageColor,
    this.suffixSvgImageWidth = 24,
    this.suffixSvgImageHeight = 24,
    this.suffixSvgImageColor,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(leftPadding, topPadding, rightPadding, bottomPadding),
      child: ElevatedButton(
        onPressed: isDisabled ? null : onTap,
        style: ElevatedButton.styleFrom(
          backgroundColor: isDisabled ? CustomColors.disable : btnColor,
          minimumSize: Size(width ?? Get.width * 0.2, height ?? 48),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(borderRadius),
            side: BorderSide(color: borderColor, width: 1),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 16),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (prefixIcon != null) ...[
              Icon(
                prefixIcon,
                color: prefixIconColor,
                size: prefixIconSize,
              ),
              SizedBox(width: prefixTextGap),
            ],
            if (prefixImage != null) ...[
              Image(
                image: prefixImage!,
                height: prefixImageWidth,
                width: prefixImageHeight,
              ),
              SizedBox(width: prefixTextGap),
            ],
            if (prefixSvgImage != null) ...[
              SvgPicture.asset(
                prefixSvgImage!,
                width: prefixSvgImageWidth,
                height: prefixSvgImageHeight,
                fit: BoxFit.fill,
                colorFilter: prefixSvgImageColor != null ? ColorFilter.mode(prefixSvgImageColor!, BlendMode.srcIn) : null,
              ),
              SizedBox(width: prefixTextGap),
            ],
            Text(
              btnText,
              style: TextStyle(
                color: textColor,
                fontSize: textSize,
                fontFamily: fontFamily,
              ),
            ),
            if (suffixIcon != null) ...[
              const SizedBox(width: 8),
              Icon(
                suffixIcon,
                color: suffixIconColor,
                size: suffixIconSize,
              ),
            ],
            if (suffixImage != null) ...[
              const SizedBox(width: 8),
              Image(
                image: suffixImage!,
                height: suffixImageWidth,
                width: suffixImageHeight,
              ),
            ],
            if (suffixSvgImage != null) ...[
              const SizedBox(width: 8),
              SvgPicture.asset(
                suffixSvgImage!,
                width: suffixSvgImageWidth,
                height: suffixSvgImageHeight,
                fit: BoxFit.fill,
                colorFilter: ColorFilter.mode(suffixSvgImageColor ?? CustomColors.white, BlendMode.srcIn),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
