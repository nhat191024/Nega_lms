import 'package:nega_lms/utils/imports.dart';

class NotificationDialog extends StatelessWidget {
  final String title;
  final String message;

  const NotificationDialog({
    super.key,
    required this.title,
    required this.message,
  });

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
      ),
      child: Container(
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              title,
              style: const TextStyle(
                fontSize: 18,
                color: CustomColors.primary,
                fontFamily: FontStyleTextStrings.medium,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              message,
              style: const TextStyle(
                fontSize: 14,
                color: CustomColors.primaryText,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
            const SizedBox(height: 20),
            CustomButton(
              onTap: () {
                Get.back();
              },
              btnText: 'Đóng',
              width: 100,
              height: 40,
              textSize: 14,
              textColor: CustomColors.white,
              fontFamily: FontStyleTextStrings.medium,
              btnColor: CustomColors.primary,
              borderColor: CustomColors.primary,
              borderRadius: 5,
            ),
          ],
        ),
      ),
    );
  }
}
