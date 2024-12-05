import 'package:nega_lms/utils/imports.dart';

class DoAssignmentScreen extends GetView<AssignmentController> {
  const DoAssignmentScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        title: const NavBar(),
      ),
      backgroundColor: CustomColors.background,
      body: Obx(
        () => controller.isLoading.value
            ? const Center(
                child: CircularProgressIndicator(),
              )
            : Padding(
                padding: const EdgeInsets.fromLTRB(100, 60, 100, 0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: Get.width * 0.15,
                          height: Get.height * 0.4,
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    controller.assignment.value.name!,
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontFamily: FontStyleTextStrings.medium,
                                      color: CustomColors.primary,
                                    ),
                                  ),
                                  const SizedBox(height: 10),
                                  Text(
                                    "Tên thí sinh: ${controller.assignment.value.creatorName}",
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontFamily: FontStyleTextStrings.medium,
                                      color: CustomColors.primary,
                                    ),
                                  ),
                                  const SizedBox(height: 5),
                                  SizedBox(
                                    width: Get.width * 0.12,
                                    child: const Divider(thickness: 1, color: CustomColors.primary),
                                  ),
                                  const Text("Thời gian làm bài thi",
                                      style: TextStyle(
                                        fontSize: 16,
                                        fontFamily: FontStyleTextStrings.medium,
                                        color: CustomColors.primary,
                                      )),
                                  const SizedBox(height: 5),
                                  Obx(() => Text(
                                        controller.timeLeft.value,
                                        style: const TextStyle(
                                          fontSize: 24,
                                          fontFamily: FontStyleTextStrings.bold,
                                          color: CustomColors.primary,
                                        ),
                                      )),
                                  SizedBox(
                                    width: Get.width * 0.12,
                                    child: const Divider(thickness: 1, color: CustomColors.primary),
                                  ),
                                  const Spacer(),
                                  Row(
                                    children: [
                                      CustomButton(
                                        width: 100,
                                        onTap: () {},
                                        btnText: "Quay lại",
                                        btnColor: CustomColors.errorMain,
                                        borderColor: CustomColors.errorMain,
                                      ),
                                      const SizedBox(width: 10),
                                      CustomButton(
                                        width: 100,
                                        onTap: () {
                                          controller.submitAssignment();
                                        },
                                        btnText: "Kết thúc bài thi",
                                      ),
                                    ],
                                  ),
                                  const SizedBox(height: 10),
                                ],
                              )
                            ],
                          ),
                        ),
                        const SizedBox(width: 20),
                        Expanded(
                          child: Container(
                            height: Get.height * 0.4,
                            padding: const EdgeInsets.all(40),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Câu ${controller.currentQuestion.value + 1}",
                                      style: const TextStyle(
                                        fontSize: 18,
                                        fontFamily: FontStyleTextStrings.medium,
                                        color: CustomColors.primary,
                                      ),
                                    ),
                                    const SizedBox(height: 10),
                                    Text(
                                      controller.questionList[controller.currentQuestion.value].question!,
                                      style: const TextStyle(
                                        fontSize: 26,
                                        fontFamily: FontStyleTextStrings.medium,
                                        color: CustomColors.primary,
                                      ),
                                    ),
                                    const SizedBox(height: 20),
                                    ...controller.questionList[controller.currentQuestion.value].choices!.map(
                                      (choice) => Row(
                                        children: [
                                          Obx(
                                            () => Radio(
                                              value: choice.id,
                                              groupValue: controller.answerList[controller.currentQuestion.value].choiceId,
                                              onChanged: (value) {
                                                if (value != null) {
                                                  controller.saveSelection(controller.questionList[controller.currentQuestion.value].id!, value);
                                                }
                                              },
                                              activeColor: CustomColors.primary,
                                            ),
                                          ),
                                          Text(
                                            choice.choice!,
                                            style: const TextStyle(
                                              fontSize: 16,
                                              fontFamily: FontStyleTextStrings.medium,
                                              color: CustomColors.primary,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(height: 5),
                                  ],
                                )
                              ],
                            ),
                          ),
                        )
                      ],
                    ),
                    const SizedBox(height: 20),
                    Expanded(
                      child: Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Column(
                          children: [
                            const Text(
                              "Danh sách câu hỏi",
                              style: TextStyle(
                                fontSize: 22,
                                fontFamily: FontStyleTextStrings.medium,
                                color: CustomColors.primary,
                              ),
                            ),
                            const SizedBox(height: 10),
                            Obx(
                              () => Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: List.generate(
                                  controller.questionList.length,
                                  (index) => Padding(
                                    padding: const EdgeInsets.symmetric(horizontal: 5),
                                    child: ElevatedButton(
                                      onPressed: () {
                                        controller.currentQuestion.value = index;
                                      },
                                      style: ElevatedButton.styleFrom(
                                        padding: const EdgeInsets.all(12),
                                        backgroundColor: controller.currentQuestion.value == index ? CustomColors.primary : Colors.white,
                                        shape: RoundedRectangleBorder(
                                          borderRadius: BorderRadius.circular(10),
                                          side: const BorderSide(
                                            color: CustomColors.primary,
                                          ),
                                        ),
                                      ),
                                      child: Text(
                                        (index + 1).toString(),
                                        style: TextStyle(
                                          fontSize: 16,
                                          fontFamily: FontStyleTextStrings.medium,
                                          color: controller.currentQuestion.value == index ? Colors.white : CustomColors.primary,
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ),
                            const Spacer(),
                            Padding(
                              padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 40),
                              child: Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  CustomButton(
                                    width: 100,
                                    onTap: () {
                                      if (controller.currentQuestion.value > 0) {
                                        controller.currentQuestion.value--;
                                      }
                                    },
                                    btnText: "Câu trước",
                                    btnColor: CustomColors.primary,
                                    borderColor: CustomColors.primary,
                                  ),
                                  const SizedBox(width: 10),
                                  CustomButton(
                                    width: 100,
                                    onTap: () {
                                      if (controller.currentQuestion.value < controller.questionList.length - 1) {
                                        controller.currentQuestion.value++;
                                      }
                                    },
                                    btnText: "Câu sau",
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 60),
                  ],
                ),
              ),
      ),
    );
  }
}
