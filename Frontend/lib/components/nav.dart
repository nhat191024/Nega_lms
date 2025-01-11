import 'package:nega_lms/utils/imports.dart';

class NavBar extends StatelessWidget {
  final GlobalKey<ScaffoldState> scaffoldKey;

  const NavBar({super.key, required this.scaffoldKey});

  @override
  Widget build(BuildContext context) {
    final controllers = Get.put(NavController());
    final isMobile = MediaQuery.of(context).size.width < 768;
    return Container(
      decoration: const BoxDecoration(
        border: Border(
          bottom: BorderSide(
            color: CustomColors.border,
            width: 1,
          ),
        ),
      ),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 10),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            if (isMobile)
              IconButton(
                onPressed: () {
                  scaffoldKey.currentState?.openDrawer();
                },
                icon: const Icon(Icons.menu_rounded),
                color: CustomColors.primary,
                iconSize: 30,
              ),
            const Row(
              children: [
                CircleAvatar(
                  radius: 20,
                  backgroundImage: AssetImage(Images.logoNoBg),
                  backgroundColor: Colors.transparent,
                ),
                SizedBox(width: 10),
                Text(
                  'NegaLMS',
                  style: TextStyle(
                    color: CustomColors.primary,
                    fontSize: 18,
                    fontFamily: FontStyleTextStrings.bold,
                  ),
                ),
              ],
            ),
            Container(
              height: 44,
              width: 44,
              margin: const EdgeInsets.only(right: 10),
              decoration: const BoxDecoration(
                color: Colors.blueAccent,
                shape: BoxShape.circle,
              ),
              clipBehavior: Clip.antiAlias,
              child: Image.network(
                // controllers.avatar.value,
                //for dev only
                "https://imgur.com/a/H345YaA",
                fit: BoxFit.cover,
              ),
            ),
          ],
        ),
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
