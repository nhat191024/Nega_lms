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
            onTap: () {
              Get.toNamed(Routes.loginPage);
            },
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

class Footer extends StatelessWidget {
  const Footer({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: Get.width,
      padding: const EdgeInsets.fromLTRB(150, 60, 200, 20),
      margin: const EdgeInsets.only(top: 20),
      decoration: const BoxDecoration(
        color: CustomColors.footer,
        // borderRadius: BorderRadius.,
      ),
      child: Column(
        children: [
          Row(
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const CircleAvatar(
                    radius: 20,
                    backgroundImage: AssetImage(Images.logoNoBg),
                    backgroundColor: Colors.transparent,
                  ),
                  const Text(
                    'NegaLMS',
                    style: TextStyle(
                      color: CustomColors.primary,
                      fontSize: 16,
                      fontFamily: FontStyleTextStrings.bold,
                    ),
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    'Nền tảng thi trắc nghiệm\nonline tốt nhất',
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.regular,
                    ),
                  ),
                  const SizedBox(height: 40),
                  const Text(
                    "Tải xuống",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.regular,
                    ),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Image.asset(
                        Images.appleStore,
                        width: 160,
                      ),
                      const SizedBox(width: 15),
                      Image.asset(
                        Images.googlePlay,
                        width: 160,
                      ),
                    ],
                  ),
                ],
              ),
              const Spacer(),
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Khám phá",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 25,
                      fontFamily: FontStyleTextStrings.bold,
                    ),
                  ),
                  SizedBox(height: 35),
                  Text(
                    "Dành cho sinh viên",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Dành cho trung tâm, trường học",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Dành cho doanh nghiệp",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Dành cho học viên",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 80),
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Tài nguyên",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 25,
                      fontFamily: FontStyleTextStrings.bold,
                    ),
                  ),
                  SizedBox(height: 35),
                  Text(
                    "Tin tức",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Giới thiệu",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Hướng dẫn",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Câu hỏi thường gặp",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 80),
              const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Chính sách",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 25,
                      fontFamily: FontStyleTextStrings.bold,
                    ),
                  ),
                  SizedBox(height: 35),
                  Text(
                    "Điều khoản sử dụng",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Chính sách bảo mật",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Chính sách sử dụng",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                  SizedBox(height: 25),
                  Text(
                    "Hướng dẫn sử dụng",
                    style: TextStyle(
                      color: CustomColors.white,
                      fontSize: 18,
                      fontFamily: FontStyleTextStrings.light,
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 20),
          Center(
            child: Column(
              children: [
                SizedBox(
                  width: Get.width * 0.4,
                  child: const Divider(
                    color: CustomColors.primary,
                    thickness: 1,
                  ),
                ),
                const SizedBox(height: 20),
                const Text(
                  '© 2024 NegaLMS. All rights reserved.',
                  style: TextStyle(
                    color: CustomColors.white,
                    fontSize: 14,
                    fontFamily: FontStyleTextStrings.regular,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
