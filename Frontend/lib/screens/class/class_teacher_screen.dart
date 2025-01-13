import 'package:nega_lms/utils/imports.dart';

class ClassTeacherScreen extends GetView<ClassDetailController> {
  const ClassTeacherScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(10, 10, 0, 0),
            child: PopupMenuButton<String>(
              onSelected: (value) {
                if (value == 'Bộ câu hỏi (Quiz)') {
                  controller.clear();
                  controller.assignmentType.value = 'quiz';
                  _showAddQuizModal(context);
                } else if (value == 'Bài tập 1 câu trả lời') {
                  controller.clear();
                  controller.assignmentType.value = 'link';
                  _showAddLinkHomeworkModal(context);
                  controller.createAssignmentThenPushToClass.value = true;
                } else {
                  controller.clear();
                  controller.assignmentType.value = 'quiz_bank';
                  _showAddQuizFromBankModal(context);
                  controller.createAssignmentThenPushToClass.value = true;
                }
              },
              itemBuilder: (BuildContext context) {
                return {'Bộ câu hỏi (Quiz)', 'Bài tập 1 câu trả lời', 'Bài tập từ kho quiz'}.map((String choice) {
                  return PopupMenuItem<String>(
                    value: choice,
                    textStyle: const TextStyle(
                      color: CustomColors.white,
                      fontFamily: FontStyleTextStrings.regular,
                      fontSize: 16,
                    ),
                    child: Text(choice),
                  );
                }).toList();
              },
              offset: const Offset(5, 45),
              color: CustomColors.primary,
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10),
              ),
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 12),
                decoration: BoxDecoration(
                  color: CustomColors.primary,
                  borderRadius: BorderRadius.circular(50),
                ),
                child: const Text(
                  "Tạo bài tập",
                  style: TextStyle(
                    fontSize: 16,
                    color: CustomColors.white,
                    fontFamily: FontStyleTextStrings.medium,
                  ),
                ),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(60, 20, 60, 0),
            child: SizedBox(
              height: Get.height * 0.8,
              child: Obx(
                () => controller.isLoading.value
                    ? const Center(
                        child: CircularProgressIndicator(),
                      )
                    : ListView.builder(
                        shrinkWrap: true,
                        physics: const AlwaysScrollableScrollPhysics(),
                        itemCount: controller.assignmentList.length,
                        itemBuilder: (context, index) {
                          return classCardBuilder(
                            context,
                            controller.assignmentList[index].title ?? '',
                            controller.assignmentList[index].description ?? '',
                            controller.assignmentList[index].duration ?? '',
                            10,
                            index == controller.assignmentList.length - 1,
                            controller.assignmentList[index].id.toString(),
                            controller.assignmentList[index].type ?? '',
                            controller.assignmentList[index].id.toString(),
                          );
                        },
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  //class card builder
  Widget classCardBuilder(
    context,
    String title,
    String description,
    String duration,
    double verticalPadding,
    bool isLast,
    String id,
    String type,
    String homeworkId,
  ) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: verticalPadding),
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: const TextStyle(
                        fontSize: 16,
                        color: CustomColors.primaryText,
                        fontFamily: FontStyleTextStrings.medium,
                      ),
                    ),
                    const SizedBox(height: 5),
                    Text(
                      description,
                      style: const TextStyle(
                        fontSize: 14,
                        color: CustomColors.secondaryText,
                        fontFamily: FontStyleTextStrings.regular,
                      ),
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                          margin: const EdgeInsets.only(right: 10),
                          decoration: BoxDecoration(
                            color: CustomColors.primary,
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: Text(
                            "Loại bài tập: $type",
                            style: const TextStyle(
                              fontSize: 12,
                              color: CustomColors.background,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                          margin: const EdgeInsets.only(right: 10),
                          decoration: BoxDecoration(
                            color: CustomColors.primary,
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: Text(
                            "Thời gian làm: $duration",
                            style: const TextStyle(
                              fontSize: 12,
                              color: CustomColors.background,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 20),
              CustomButton(
                onTap: () async {
                  await controller.loadDataToEdit(homeworkId, type);
                  type == "quiz"
                      ? _showAddQuizFromBankModal(context, isEdit: true, homeworkId: homeworkId, type: type)
                      : _showAddLinkHomeworkModal(context, isEdit: true);
                },
                btnText: 'Chỉnh sửa',
                btnColor: CustomColors.primary,
                width: 140,
              ),
            ],
          ),
          const SizedBox(height: 20),
          if (!isLast) const Divider(),
        ],
      ),
    );
  }

  Future _showAddQuizModal(context) {
    return showGeneralDialog(
      context: context,
      barrierColor: Colors.black.withOpacity(0.5),
      barrierDismissible: true,
      barrierLabel: MaterialLocalizations.of(context).modalBarrierDismissLabel,
      pageBuilder: (context, animation, secondaryAnimation) {
        return PopScope(
          canPop: true,
          onPopInvokedWithResult: (didPop, result) {
            if (didPop) {
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.75,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: SingleChildScrollView(
                  child: Obx(
                    () => Column(
                      children: [
                        Stack(
                          children: [
                            const Align(
                              alignment: Alignment.center,
                              child: Padding(
                                padding: EdgeInsets.only(top: 15),
                                child: Text(
                                  "Tạo bộ câu hỏi",
                                  style: TextStyle(
                                    fontSize: 22,
                                    color: CustomColors.primary,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            ),
                            Align(
                              alignment: Alignment.centerRight,
                              child: CustomButton(
                                onTap: () => controller.createQuiz(),
                                btnText: 'Tạo bài tập',
                                btnColor: CustomColors.primary,
                                width: 200,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Tên bài tập",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentNameError.value,
                          isError: controller.isAssignmentNameError.value.obs,
                          width: Get.width * 0.62,
                          obscureText: false.obs,
                          controller: controller.assignmentName,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentNameError.value = false;
                            } else {
                              controller.isAssignmentNameError.value = true;
                              controller.assignmentNameError.value = "Câu hỏi không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 20),
                        Row(
                          children: [
                            DateTimeField(
                              labelText: "Thời gian bắt đầu",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentStartDateError.value,
                              isError: controller.isAssignmentStartDateError.value.obs,
                              width: Get.width * 0.3,
                              leftPadding: 55,
                              controller: controller.assignmentStartDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentStartDateError.value = false;
                                } else {
                                  controller.isAssignmentStartDateError.value = true;
                                  controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                                }
                              },
                            ),
                            DateTimeField(
                              labelText: "Thời gian kết thúc",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDueDateError.value,
                              isError: controller.isAssignmentDueDateError.value.obs,
                              width: Get.width * 0.3,
                              controller: controller.assignmentDueDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDueDateError.value = false;
                                } else {
                                  controller.isAssignmentDueDateError.value = true;
                                  controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CustomTextField(
                              labelText: "Thời gian làm bài",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDurationError.value,
                              isError: controller.isAssignmentDurationError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.assignmentDuration,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDurationError.value = false;
                                } else {
                                  controller.isAssignmentDurationError.value = true;
                                  controller.assignmentDurationError.value = "Thời gian làm bài không được để trống";
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Trạng thái",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentStatusError.value,
                              isError: controller.isAssignmentStatusError.value.obs,
                              width: Get.width * 0.3,
                              value: controller.assignmentStatus.value,
                              items: const [
                                DropdownMenuItem(value: 'true', child: Text('Hiện')),
                                DropdownMenuItem(value: 'false', child: Text('Ẩn')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentStatus.value = value;
                                  controller.isAssignmentStatusError.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Mô tả",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentDescriptionError.value,
                          isError: controller.isAssignmentDescriptionError.value.obs,
                          width: Get.width * 0.62,
                          minLines: 4,
                          maxLines: 4,
                          obscureText: false.obs,
                          controller: controller.assignmentDescription,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentDescriptionError.value = false;
                            } else {
                              controller.isAssignmentDescriptionError.value = true;
                              controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 40),
                        const Text(
                          "Câu hỏi",
                          style: TextStyle(
                            fontSize: 20,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        ),
                        const SizedBox(height: 10),
                        Obx(
                          () => Column(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              ...List.generate(
                                controller.questions.length,
                                (qIndex) => Padding(
                                  padding: const EdgeInsets.only(bottom: 20),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.center,
                                    children: [
                                      Row(
                                        children: [
                                          IconButton(
                                            icon: const Icon(Icons.remove_circle_outline),
                                            color: CustomColors.primary,
                                            onPressed: () {
                                              controller.removeQuestion(qIndex);
                                            },
                                          ),
                                          Expanded(
                                            child: CustomTextField(
                                              labelText: "Câu hỏi ${qIndex + 1}",
                                              labelColor: CustomColors.primary,
                                              labelSize: 16,
                                              hintText: "Nhập câu hỏi",
                                              errorText: "",
                                              isError: false.obs,
                                              width: Get.width * 0.45,
                                              obscureText: false.obs,
                                              controller: controller.questions[qIndex]['question'],
                                              onChanged: (value) {},
                                            ),
                                          ),
                                          CustomTextField(
                                            labelText: "Điểm số",
                                            labelColor: CustomColors.primary,
                                            labelSize: 16,
                                            hintText: "Nhập điểm",
                                            errorText: "",
                                            isError: false.obs,
                                            width: Get.width * 0.1,
                                            obscureText: false.obs,
                                            controller: controller.questions[qIndex]['score'],
                                            keyboardType: TextInputType.number,
                                            disable: true,
                                            onChanged: (value) {},
                                          ),
                                          IconButton(
                                            icon: const Icon(Icons.add_circle_outline),
                                            color: CustomColors.primary,
                                            onPressed: () {
                                              controller.addAnswerToQuestion(qIndex);
                                            },
                                          ),
                                        ],
                                      ),
                                      const SizedBox(height: 10),
                                      Obx(
                                        () {
                                          final answers = controller.questions[qIndex]['answers'] as RxList<Map<String, dynamic>>;
                                          return Column(
                                            children: List.generate(
                                              answers.length,
                                              (aIndex) => Padding(
                                                padding: const EdgeInsets.only(left: 30, bottom: 10),
                                                child: Row(
                                                  children: [
                                                    Obx(() => Checkbox(
                                                          value: (answers[aIndex]['isCorrect'] as RxBool).value,
                                                          onChanged: (bool? value) {
                                                            controller.toggleAnswerCorrect(qIndex, aIndex);
                                                          },
                                                          activeColor: CustomColors.primary,
                                                        )),
                                                    Expanded(
                                                      child: CustomTextField(
                                                        labelText: "Câu trả lời ${aIndex + 1}",
                                                        labelColor: CustomColors.secondaryText,
                                                        labelSize: 14,
                                                        hintText: "Nhập câu trả lời",
                                                        errorText: "",
                                                        isError: false.obs,
                                                        width: Get.width * 0.5,
                                                        obscureText: false.obs,
                                                        controller: answers[aIndex]['controller'],
                                                        onChanged: (value) {},
                                                      ),
                                                    ),
                                                    IconButton(
                                                      icon: const Icon(Icons.remove_circle_outline),
                                                      color: CustomColors.primary,
                                                      onPressed: () {
                                                        controller.removeAnswer(qIndex, aIndex);
                                                      },
                                                    ),
                                                  ],
                                                ),
                                              ),
                                            ),
                                          );
                                        },
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                              Center(
                                child: IconButton(
                                  icon: const Icon(Icons.add_circle_outline, size: 30),
                                  color: CustomColors.primary,
                                  onPressed: () => controller.addNewQuestion(),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Future _showAddLinkHomeworkModal(context, {isEdit = false, homeworkId = '', type = ''}) {
    return showGeneralDialog(
      context: context,
      barrierColor: Colors.black.withOpacity(0.5),
      barrierDismissible: true,
      barrierLabel: MaterialLocalizations.of(context).modalBarrierDismissLabel,
      pageBuilder: (context, animation, secondaryAnimation) {
        return PopScope(
          canPop: true,
          onPopInvokedWithResult: (didPop, result) {
            if (didPop) {
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.65,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Obx(
                  () => Column(
                    children: [
                      Text(
                        isEdit ? "Chỉnh sửa bài tập 1 câu trả lời" : "Tạo bài tập 1 câu trả lời",
                        style: const TextStyle(
                          fontSize: 22,
                          color: CustomColors.primary,
                          fontFamily: FontStyleTextStrings.medium,
                        ),
                      ),
                      const SizedBox(height: 20),
                      CustomTextField(
                        labelText: "Tên bài tập",
                        labelColor: CustomColors.primary,
                        labelSize: 18,
                        hintText: "nhập",
                        errorText: controller.assignmentNameError.value,
                        isError: controller.isAssignmentNameError.value.obs,
                        width: Get.width * 0.62,
                        obscureText: false.obs,
                        controller: controller.assignmentName,
                        onChanged: (value) {
                          if (value.isNotEmpty) {
                            controller.isAssignmentNameError.value = false;
                          } else {
                            controller.isAssignmentNameError.value = true;
                            controller.assignmentNameError.value = "Câu hỏi không được để trống";
                          }
                        },
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          DateTimeField(
                            labelText: "Thời gian bắt đầu",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            errorText: controller.assignmentStartDateError.value,
                            isError: controller.isAssignmentStartDateError.value.obs,
                            width: Get.width * 0.3,
                            leftPadding: 55,
                            controller: controller.assignmentStartDate,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isAssignmentStartDateError.value = false;
                              } else {
                                controller.isAssignmentStartDateError.value = true;
                                controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                              }
                            },
                          ),
                          DateTimeField(
                            labelText: "Thời gian kết thúc",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            errorText: controller.assignmentDueDateError.value,
                            isError: controller.isAssignmentDueDateError.value.obs,
                            width: Get.width * 0.3,
                            controller: controller.assignmentDueDate,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isAssignmentDueDateError.value = false;
                              } else {
                                controller.isAssignmentDueDateError.value = true;
                                controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                              }
                            },
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      CustomTextField(
                        labelText: "Mô tả",
                        labelColor: CustomColors.primary,
                        labelSize: 18,
                        hintText: "nhập",
                        errorText: controller.assignmentDescriptionError.value,
                        isError: controller.isAssignmentDescriptionError.value.obs,
                        width: Get.width * 0.62,
                        minLines: 4,
                        maxLines: 4,
                        obscureText: false.obs,
                        controller: controller.assignmentDescription,
                        onChanged: (value) {
                          if (value.isNotEmpty) {
                            controller.isAssignmentDescriptionError.value = false;
                          } else {
                            controller.isAssignmentDescriptionError.value = true;
                            controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                          }
                        },
                      ),
                      const SizedBox(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          SelectBox(
                            labelText: "Trạng thái",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "Chọn",
                            errorText: controller.assignmentStatusError.value,
                            isError: controller.isAssignmentStatusError.value.obs,
                            width: Get.width * 0.3,
                            value: controller.assignmentStatus.value,
                            items: const [
                              DropdownMenuItem(value: 'true', child: Text('Hiện')),
                              DropdownMenuItem(value: 'false', child: Text('Ẩn')),
                            ],
                            onChanged: (value) {
                              if (value != null) {
                                controller.assignmentStatus.value = value;
                                controller.isAssignmentStatusError.value = false;
                              }
                            },
                          ),
                        ],
                      ),
                      const Spacer(),
                      CustomButton(
                        onTap: () => isEdit ? controller.updateQuiz(homeworkId, type) : controller.createQuiz(),
                        btnText: isEdit ? 'Chỉnh sửa bài tập' : 'Tạo bài tập',
                        btnColor: CustomColors.primary,
                        width: 200,
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Future _showAddQuizFromBankModal(context, {isEdit = false, homeworkId = '', type = ''}) {
    return showGeneralDialog(
      context: context,
      barrierColor: Colors.black.withOpacity(0.5),
      barrierDismissible: true,
      barrierLabel: MaterialLocalizations.of(context).modalBarrierDismissLabel,
      pageBuilder: (context, animation, secondaryAnimation) {
        return PopScope(
          canPop: true,
          onPopInvokedWithResult: (didPop, result) {
            if (didPop) {
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.75,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: SingleChildScrollView(
                  child: Obx(
                    () => Column(
                      children: [
                        Stack(
                          children: [
                            Align(
                              alignment: Alignment.center,
                              child: Padding(
                                padding: const EdgeInsets.only(top: 15),
                                child: Text(
                                  isEdit ? "Chỉnh sửa bài tập" : "Tạo bài tập từ kho quiz",
                                  style: const TextStyle(
                                    fontSize: 22,
                                    color: CustomColors.primary,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            ),
                            Align(
                              alignment: Alignment.centerRight,
                              child: CustomButton(
                                onTap: () => isEdit ? controller.updateQuiz(homeworkId, type) : controller.createQuiz(),
                                btnText: isEdit ? 'Sửa bài tập' : 'Tạo bài tập',
                                btnColor: CustomColors.primary,
                                width: 200,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Tên bài tập",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentNameError.value,
                          isError: controller.isAssignmentNameError.value.obs,
                          width: Get.width * 0.62,
                          obscureText: false.obs,
                          controller: controller.assignmentName,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentNameError.value = false;
                            } else {
                              controller.isAssignmentNameError.value = true;
                              controller.assignmentNameError.value = "Câu hỏi không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 20),
                        Row(
                          children: [
                            DateTimeField(
                              labelText: "Thời gian bắt đầu",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentStartDateError.value,
                              isError: controller.isAssignmentStartDateError.value.obs,
                              width: Get.width * 0.3,
                              leftPadding: 55,
                              controller: controller.assignmentStartDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentStartDateError.value = false;
                                } else {
                                  controller.isAssignmentStartDateError.value = true;
                                  controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                                }
                              },
                            ),
                            DateTimeField(
                              labelText: "Thời gian kết thúc",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDueDateError.value,
                              isError: controller.isAssignmentDueDateError.value.obs,
                              width: Get.width * 0.3,
                              controller: controller.assignmentDueDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDueDateError.value = false;
                                } else {
                                  controller.isAssignmentDueDateError.value = true;
                                  controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CustomTextField(
                              labelText: "Thời gian làm bài",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDurationError.value,
                              isError: controller.isAssignmentDurationError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.assignmentDuration,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDurationError.value = false;
                                } else {
                                  controller.isAssignmentDurationError.value = true;
                                  controller.assignmentDurationError.value = "Thời gian làm bài không được để trống";
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Trạng thái",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentStatusError.value,
                              isError: controller.isAssignmentStatusError.value.obs,
                              width: Get.width * 0.3,
                              value: controller.assignmentStatus.value,
                              items: const [
                                DropdownMenuItem(value: 'true', child: Text('Hiện')),
                                DropdownMenuItem(value: 'false', child: Text('Ẩn')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentStatus.value = value;
                                  controller.isAssignmentStatusError.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Mô tả",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentDescriptionError.value,
                          isError: controller.isAssignmentDescriptionError.value.obs,
                          width: Get.width * 0.62,
                          minLines: 4,
                          maxLines: 4,
                          obscureText: false.obs,
                          controller: controller.assignmentDescription,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentDescriptionError.value = false;
                            } else {
                              controller.isAssignmentDescriptionError.value = true;
                              controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 40),
                        Stack(
                          children: [
                            Obx(
                              () => controller.step.value == '1'
                                  ? const SizedBox.shrink()
                                  : Align(
                                      alignment: Alignment.centerLeft,
                                      child: CustomButton(
                                        onTap: () {
                                          if (controller.step.value == '2') {
                                            controller.step.value = '1';
                                          } else {
                                            controller.step.value = '2';
                                          }
                                        },
                                        btnText: "Quay lại",
                                        btnColor: CustomColors.primary,
                                        width: 200,
                                      ),
                                    ),
                            ),
                            const Align(
                              alignment: Alignment.center,
                              child: Padding(
                                padding: EdgeInsets.only(top: 15),
                                child: Text(
                                  "Câu hỏi",
                                  style: TextStyle(
                                    fontSize: 20,
                                    color: CustomColors.primary,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            ),
                            Obx(
                              () => controller.step.value == '1'
                                  ? Align(
                                      alignment: Alignment.centerRight,
                                      child: CustomButton(
                                        onTap: () {
                                          controller.step2();
                                        },
                                        btnText: isEdit ? 'Thay đổi câu hỏi' : 'Thêm câu hỏi',
                                        btnColor: CustomColors.primary,
                                        width: 200,
                                      ),
                                    )
                                  : const SizedBox.shrink(),
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        switch (controller.step.value) {
                          '1' => Padding(
                              padding: const EdgeInsets.fromLTRB(20, 0, 20, 20),
                              child: controller.quizzes.isEmpty
                                  ? const Center(
                                      child: Text(
                                        "Chưa có câu hỏi nào",
                                        style: TextStyle(
                                          fontSize: 16,
                                          color: CustomColors.primary,
                                          fontFamily: FontStyleTextStrings.medium,
                                        ),
                                      ),
                                    )
                                  : MasonryGridView.count(
                                      shrinkWrap: true,
                                      crossAxisCount: 2,
                                      crossAxisSpacing: 30,
                                      mainAxisSpacing: 30,
                                      itemCount: controller.quizzes.length,
                                      itemBuilder: (context, index) {
                                        return _buildQuizContainer(index, controller.quizzes[index]);
                                      },
                                    ),
                            ),
                          '2' => Padding(
                              padding: const EdgeInsets.fromLTRB(20, 0, 20, 20),
                              child: controller.quizPackage.isEmpty
                                  ? const Center(
                                      child: Text(
                                        "Hiện không có bộ câu hỏi nào",
                                        style: TextStyle(
                                          fontSize: 16,
                                          color: CustomColors.primary,
                                          fontFamily: FontStyleTextStrings.medium,
                                        ),
                                      ),
                                    )
                                  : MasonryGridView.count(
                                      shrinkWrap: true,
                                      crossAxisCount: 2,
                                      crossAxisSpacing: 30,
                                      mainAxisSpacing: 30,
                                      itemCount: controller.quizPackage.length,
                                      itemBuilder: (context, index) {
                                        return _buildQuizPackageContainer(index, controller.quizPackage[index]);
                                      },
                                    ),
                            ),
                          '3' => _buildStepThree(isEdit),
                          _ => const SizedBox.shrink(),
                        }
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildQuizContainer(index, quiz) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: CustomColors.primary, width: 1),
      ),
      padding: const EdgeInsets.all(20),
      child: Column(
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  "${index + 1}. ${quiz['question']}",
                  softWrap: true,
                  style: const TextStyle(
                    fontSize: 14,
                    color: CustomColors.primary,
                    fontFamily: FontStyleTextStrings.medium,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 5),
          Column(
            children: [
              ...List.generate(
                quiz['choices'].length,
                (aIndex) => Padding(
                  padding: const EdgeInsets.only(right: 10),
                  child: Row(
                    children: [
                      Checkbox(
                        value: quiz['choices'][aIndex]['isCorrect'] == 1 ? true : false,
                        onChanged: (bool? value) {},
                        activeColor: CustomColors.primary,
                      ),
                      Expanded(
                        child: Text(
                          quiz['choices'][aIndex]['choice'],
                          softWrap: true,
                          style: const TextStyle(
                            fontSize: 14,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.regular,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuizPackageContainer(index, package) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: CustomColors.primary, width: 1),
      ),
      padding: const EdgeInsets.all(20),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                package['title'],
                style: const TextStyle(
                  fontSize: 16,
                  color: CustomColors.primaryText,
                  fontFamily: FontStyleTextStrings.medium,
                ),
              ),
              const SizedBox(height: 5),
              SizedBox(
                width: Get.width * 0.2,
                child: Text(
                  package['description'],
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    fontSize: 14,
                    color: CustomColors.secondaryText,
                    fontFamily: FontStyleTextStrings.regular,
                  ),
                ),
              ),
              const SizedBox(height: 5),
              Text(
                "Số câu: ${package['totalQuizzes'].toString()}",
                style: const TextStyle(
                  fontSize: 14,
                  color: CustomColors.secondaryText,
                  fontFamily: FontStyleTextStrings.regular,
                ),
              ),
              const SizedBox(height: 5),
              Text(
                "Người tạo: ${package['creator']}",
                style: const TextStyle(
                  fontSize: 14,
                  color: CustomColors.secondaryText,
                  fontFamily: FontStyleTextStrings.regular,
                ),
              ),
              const SizedBox(height: 5),
              Text(
                "Ngày tạo: ${package['createdAt']}",
                style: const TextStyle(
                  fontSize: 14,
                  color: CustomColors.secondaryText,
                  fontFamily: FontStyleTextStrings.regular,
                ),
              ),
            ],
          ),
          const SizedBox(width: 20),
          CustomButton(
            onTap: () {
              controller.selectedPackage.value = index.toString();
              controller.step.value = '3';
            },
            btnText: 'Chọn',
            btnColor: CustomColors.primary,
            width: 140,
          ),
        ],
      ),
    );
  }

  Widget _buildStepThree(isEdit) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        const SizedBox(height: 20),
        CustomTextField(
          labelText: "Số lượng câu hỏi",
          labelColor: CustomColors.primary,
          labelSize: 18,
          hintText: "nhập",
          errorText: controller.numberOfQuizError.value,
          isError: controller.isNumberOfQuizError,
          width: Get.width * 0.62,
          obscureText: false.obs,
          controller: controller.numberOfQuiz,
          keyboardType: TextInputType.number,
          onChanged: (value) {
            if (isEdit) controller.validateQuizNumber(value);
          },
        ),
        if (isEdit) ...[
          const SizedBox(height: 10),
          Obx(
            () => CustomButton(
              onTap: () {
                controller.updateQuizzes();
              },
              btnText: 'Thay đổi số lượng câu hỏi',
              btnColor: CustomColors.primary,
              width: Get.width * 0.15,
              isLoading: controller.isUpdateQuizLoading.value,
            ),
          ),
        ],
        const SizedBox(height: 10),
      ],
    );
  }
}
