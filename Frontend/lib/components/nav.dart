import 'package:nega_lms/utils/imports.dart';

class NavBar extends StatelessWidget {
  const NavBar({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 0),
      child: Row(
        children: [
          const CircleAvatar(
            radius: 20,
            backgroundImage: AssetImage(Images.logoNoBg),
            backgroundColor: Colors.transparent,
          ),
          const Spacer(),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Trang chủ',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Tìm kiếm đề thi',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Tạo đề thi',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Khám phá',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Tin tức',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          TextButton(
            onPressed: () {},
            child: const Text(
              'Liên hệ',
              style: TextStyle(
                color: CustomColors.primaryText,
                fontSize: 16,
                fontFamily: FontStyleTextStrings.regular,
              ),
            ),
          ),
          const SizedBox(width: 10),
          CustomButton(
            btnText: "Đăng nhập",
            onTap: () {},
            rightPadding: 0,
            leftPadding: 0,
            borderRadius: 24,
            width: Get.width * 0.07,
          )
        ],
      ),
    );
  }
}
