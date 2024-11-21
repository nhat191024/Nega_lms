import 'package:nega_lms/utils/imports.dart';

class HomeScreen extends GetView<HomeController> {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const NavBar(),
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(100, 60, 200, 0),
              child: Row(
                children: [
                  SizedBox(
                    width: Get.width * 0.28,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          padding: const EdgeInsets.fromLTRB(20, 10, 20, 10),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: const Border(
                              top: BorderSide(
                                color: CustomColors.primary,
                                width: 4.0,
                              ),
                              right: BorderSide(color: CustomColors.primary, width: 4.0),
                              bottom: BorderSide(
                                color: CustomColors.primary,
                                width: 2.0,
                              ),
                              left: BorderSide(
                                color: CustomColors.primary,
                                width: 2.0,
                              ),
                            ),
                          ),
                          child: const Text(
                            'Nền tảng thi trắc nghiệm online lớn nhất',
                            style: TextStyle(
                              color: CustomColors.primary,
                              fontSize: 16,
                              fontFamily: FontStyleTextStrings.bold,
                            ),
                          ),
                        ),
                        const SizedBox(height: 20),
                        RichText(
                          text: const TextSpan(
                            children: [
                              TextSpan(
                                text: 'Công cụ hỗ trợ ',
                                style: TextStyle(
                                  color: CustomColors.primaryText,
                                  fontSize: 30,
                                  fontFamily: FontStyleTextStrings.semiBold,
                                ),
                              ),
                              TextSpan(
                                text: 'ôn luyện và tạo ',
                                style: TextStyle(
                                  color: CustomColors.primary,
                                  fontSize: 30,
                                  fontStyle: FontStyle.italic,
                                  fontFamily: FontStyleTextStrings.semiBold,
                                ),
                              ),
                              TextSpan(
                                text: 'đề thi trung học phổ thông quốc gia và đại học',
                                style: TextStyle(
                                  color: CustomColors.primaryText,
                                  fontSize: 30,
                                  fontFamily: FontStyleTextStrings.semiBold,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 10),
                        SizedBox(
                          width: Get.width * 0.09,
                          child: const Divider(
                            color: CustomColors.primary,
                            thickness: 5,
                          ),
                        ),
                        const SizedBox(height: 10),
                        const Text(
                          'Tạo câu hỏi và đề thi nhanh với những giải pháp thông minh. Nega tận dụng sức mạnh công nghệ để nâng cao trình độ học tập của bạn.',
                          softWrap: true,
                          style: TextStyle(
                            color: CustomColors.primaryText,
                            fontSize: 16,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        ),
                        const SizedBox(height: 20),
                        Row(
                          children: [
                            CustomButton(
                              onTap: () {},
                              btnText: "Tìm kiếm đề thi",
                              prefixSvgImage: Images.searchIcon,
                              width: Get.width * 0.1,
                            ),
                            CustomButton(
                              onTap: () {},
                              btnText: "Tạo đề thi ngay",
                              prefixSvgImage: Images.penIcon,
                              prefixSvgImageColor: CustomColors.primary,
                              width: Get.width * 0.1,
                              btnColor: CustomColors.white,
                              textColor: CustomColors.primary,
                            )
                          ],
                        )
                      ],
                    ),
                  ),
                  const Spacer(),
                  Image.asset(
                    Images.homeBanner1,
                    width: Get.width * 0.4,
                  ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
    